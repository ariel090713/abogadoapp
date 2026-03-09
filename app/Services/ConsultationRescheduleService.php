<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\User;
use App\Notifications\ConsultationRescheduled;
use App\Notifications\ConsultationRescheduleDeclined;
use App\Notifications\ConsultationRescheduleRequested;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultationRescheduleService
{
    /**
     * Request a reschedule for a consultation
     */
    public function requestReschedule(
        Consultation $consultation,
        User $requestedBy,
        Carbon $proposedSchedule,
        string $reason
    ): array {
        try {
            // Validate if consultation can be rescheduled
            if (!$consultation->canBeRescheduled()) {
                return [
                    'success' => false,
                    'message' => $this->getRescheduleBlockReason($consultation),
                ];
            }

            // Validate proposed schedule is in the future
            if ($proposedSchedule->isPast()) {
                return [
                    'success' => false,
                    'message' => 'Proposed schedule must be in the future.',
                ];
            }

            // Validate proposed schedule is at least 2 hours from now (less restrictive for reschedule)
            if ($proposedSchedule->diffInHours(now()) < 2) {
                return [
                    'success' => false,
                    'message' => 'Proposed schedule must be at least 2 hours from now.',
                ];
            }

            // Check lawyer availability for the proposed schedule
            $availabilityCheck = $this->checkLawyerAvailability(
                $consultation->lawyer,
                $proposedSchedule,
                $consultation->duration
            );

            if (!$availabilityCheck['available']) {
                return [
                    'success' => false,
                    'message' => $availabilityCheck['message'],
                ];
            }

            DB::beginTransaction();

            // Store original schedule if not already stored
            if (!$consultation->original_scheduled_at) {
                $consultation->original_scheduled_at = $consultation->scheduled_at;
            }

            // Determine if auto-accept is enabled
            $otherParty = $requestedBy->id === $consultation->client_id 
                ? $consultation->lawyer 
                : $consultation->client;

            $autoAccept = false;
            
            // Check if lawyer has auto-accept enabled (only for lawyer's reschedule requests)
            if ($requestedBy->id === $consultation->lawyer_id) {
                $autoAccept = $consultation->lawyer->lawyerProfile->auto_accept_bookings ?? false;
            }

            if ($autoAccept) {
                // Auto-accept: Update schedule immediately
                $consultation->update([
                    'scheduled_at' => $proposedSchedule,
                    'reschedule_status' => null,
                    'reschedule_requested_by' => null,
                    'reschedule_requested_at' => null,
                    'proposed_scheduled_at' => null,
                    'reschedule_reason' => null,
                    'reschedule_decline_reason' => null,
                    'reschedule_count' => $consultation->reschedule_count + 1,
                ]);

                // Notify other party
                $otherParty->notify(new ConsultationRescheduled($consultation, true));

                Log::info('Consultation auto-rescheduled', [
                    'consultation_id' => $consultation->id,
                    'requested_by' => $requestedBy->id,
                    'new_schedule' => $proposedSchedule,
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Consultation has been rescheduled successfully.',
                    'auto_accepted' => true,
                ];
            } else {
                // Manual approval: Set pending status
                $consultation->update([
                    'reschedule_status' => 'pending',
                    'reschedule_requested_by' => $requestedBy->id,
                    'reschedule_requested_at' => now(),
                    'proposed_scheduled_at' => $proposedSchedule,
                    'reschedule_reason' => $reason,
                    'reschedule_decline_reason' => null,
                ]);

                // Notify other party
                $otherParty->notify(new ConsultationRescheduleRequested($consultation));

                Log::info('Consultation reschedule requested', [
                    'consultation_id' => $consultation->id,
                    'requested_by' => $requestedBy->id,
                    'proposed_schedule' => $proposedSchedule,
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Reschedule request sent. Waiting for approval.',
                    'auto_accepted' => false,
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Reschedule request failed', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to request reschedule. Please try again.',
            ];
        }
    }

    /**
     * Approve a reschedule request
     */
    public function approveReschedule(Consultation $consultation, User $approvedBy): array
    {
        try {
            if ($consultation->reschedule_status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'No pending reschedule request found.',
                ];
            }

            // Verify the approver is the other party
            $requester = $consultation->rescheduleRequestedBy;
            if ($approvedBy->id === $requester->id) {
                return [
                    'success' => false,
                    'message' => 'You cannot approve your own reschedule request.',
                ];
            }

            DB::beginTransaction();

            // Update consultation schedule
            $consultation->update([
                'scheduled_at' => $consultation->proposed_scheduled_at,
                'reschedule_status' => null,
                'reschedule_requested_by' => null,
                'reschedule_requested_at' => null,
                'proposed_scheduled_at' => null,
                'reschedule_reason' => null,
                'reschedule_decline_reason' => null,
                'reschedule_count' => $consultation->reschedule_count + 1,
            ]);

            // Notify requester
            $requester->notify(new ConsultationRescheduled($consultation, false));

            Log::info('Consultation reschedule approved', [
                'consultation_id' => $consultation->id,
                'approved_by' => $approvedBy->id,
                'new_schedule' => $consultation->scheduled_at,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Reschedule request approved successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Reschedule approval failed', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to approve reschedule. Please try again.',
            ];
        }
    }

    /**
     * Decline a reschedule request
     */
    public function declineReschedule(
        Consultation $consultation,
        User $declinedBy,
        string $reason
    ): array {
        try {
            if ($consultation->reschedule_status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'No pending reschedule request found.',
                ];
            }

            // Verify the decliner is the other party
            $requester = $consultation->rescheduleRequestedBy;
            if ($declinedBy->id === $requester->id) {
                return [
                    'success' => false,
                    'message' => 'You cannot decline your own reschedule request.',
                ];
            }

            DB::beginTransaction();

            // Clear reschedule request
            $consultation->update([
                'reschedule_status' => null,
                'reschedule_requested_by' => null,
                'reschedule_requested_at' => null,
                'proposed_scheduled_at' => null,
                'reschedule_reason' => null,
                'reschedule_decline_reason' => $reason,
            ]);

            // Notify requester
            $requester->notify(new ConsultationRescheduleDeclined($consultation));

            Log::info('Consultation reschedule declined', [
                'consultation_id' => $consultation->id,
                'declined_by' => $declinedBy->id,
                'reason' => $reason,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Reschedule request declined.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Reschedule decline failed', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to decline reschedule. Please try again.',
            ];
        }
    }

    /**
     * Cancel a reschedule request (by requester)
     */
    public function cancelRescheduleRequest(Consultation $consultation, User $user): array
    {
        try {
            if ($consultation->reschedule_status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'No pending reschedule request found.',
                ];
            }

            // Verify the user is the requester
            if ($consultation->reschedule_requested_by !== $user->id) {
                return [
                    'success' => false,
                    'message' => 'You can only cancel your own reschedule request.',
                ];
            }

            DB::beginTransaction();

            // Clear reschedule request
            $consultation->update([
                'reschedule_status' => null,
                'reschedule_requested_by' => null,
                'reschedule_requested_at' => null,
                'proposed_scheduled_at' => null,
                'reschedule_reason' => null,
                'reschedule_decline_reason' => null,
            ]);

            Log::info('Consultation reschedule request cancelled', [
                'consultation_id' => $consultation->id,
                'cancelled_by' => $user->id,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Reschedule request cancelled.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Reschedule cancellation failed', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to cancel reschedule request. Please try again.',
            ];
        }
    }

    /**
     * Get reason why reschedule is blocked
     */
    private function getRescheduleBlockReason(Consultation $consultation): string
    {
        if (!in_array($consultation->status, ['scheduled', 'payment_pending'])) {
            return 'Consultation cannot be rescheduled in its current status.';
        }

        if ($consultation->reschedule_count >= 2) {
            return 'Maximum reschedule limit (2) has been reached.';
        }

        if ($consultation->reschedule_status === 'pending') {
            return 'There is already a pending reschedule request.';
        }

        if ($consultation->scheduled_at && $consultation->scheduled_at->diffInHours(now()) < 2) {
            return 'Cannot reschedule within 2 hours of the consultation.';
        }

        return 'Consultation cannot be rescheduled at this time.';
    }

    /**
     * Check if lawyer is available at the proposed schedule
     */
    private function checkLawyerAvailability(User $lawyer, Carbon $proposedSchedule, int $duration): array
    {
        $lawyerProfile = $lawyer->lawyerProfile;

        if (!$lawyerProfile) {
            return [
                'available' => false,
                'message' => 'Lawyer profile not found.',
            ];
        }

        // Check if lawyer is generally available
        if (!$lawyerProfile->is_available) {
            return [
                'available' => false,
                'message' => 'Lawyer is currently not accepting consultations.',
            ];
        }

        // Get day of week (0 = Sunday, 6 = Saturday)
        $dayOfWeek = $proposedSchedule->dayOfWeek;
        $proposedTime = $proposedSchedule->format('H:i:s');
        $endTime = $proposedSchedule->copy()->addMinutes($duration)->format('H:i:s');

        // Check if lawyer has availability schedule for this day
        $availabilitySchedule = $lawyerProfile->availabilitySchedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availabilitySchedule) {
            return [
                'available' => false,
                'message' => 'Lawyer is not available on ' . $proposedSchedule->format('l') . 's.',
            ];
        }

        // Check if proposed time falls within lawyer's working hours
        if ($proposedTime < $availabilitySchedule->start_time || $endTime > $availabilitySchedule->end_time) {
            return [
                'available' => false,
                'message' => 'Proposed time is outside lawyer\'s working hours (' . 
                    Carbon::parse($availabilitySchedule->start_time)->format('g:i A') . ' - ' . 
                    Carbon::parse($availabilitySchedule->end_time)->format('g:i A') . ').',
            ];
        }

        // Check for conflicting consultations
        $hasConflict = Consultation::where('lawyer_id', $lawyer->id)
            ->whereIn('status', ['scheduled', 'in_progress', 'payment_pending'])
            ->where(function ($query) use ($proposedSchedule, $duration) {
                $endTime = $proposedSchedule->copy()->addMinutes($duration);
                
                // Check if proposed time overlaps with existing consultations
                $query->where(function ($q) use ($proposedSchedule, $endTime) {
                    // Existing consultation starts during proposed time
                    $q->whereBetween('scheduled_at', [$proposedSchedule, $endTime])
                      // Or proposed time starts during existing consultation
                      ->orWhere(function ($q2) use ($proposedSchedule) {
                          $q2->where('scheduled_at', '<=', $proposedSchedule)
                             ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration MINUTE) > ?', [$proposedSchedule]);
                      });
                });
            })
            ->exists();

        if ($hasConflict) {
            return [
                'available' => false,
                'message' => 'Lawyer has another consultation scheduled at this time.',
            ];
        }

        return [
            'available' => true,
            'message' => 'Lawyer is available at the proposed schedule.',
        ];
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableTimeSlots(User $lawyer, Carbon $date, int $duration, ?int $excludeConsultationId = null): array
    {
        \Log::info('getAvailableTimeSlots called', [
            'lawyer_id' => $lawyer->id,
            'date' => $date->toDateString(),
            'duration' => $duration,
            'current_time' => now()->toDateTimeString(),
        ]);

        $lawyerProfile = $lawyer->lawyerProfile;

        if (!$lawyerProfile || !$lawyerProfile->is_available) {
            return [];
        }

        // Get day of week
        $dayOfWeek = $date->dayOfWeek;

        // Get lawyer's availability schedule for this day
        $availabilitySchedule = $lawyerProfile->availabilitySchedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availabilitySchedule) {
            return [];
        }

        // Get existing consultations for this day (exclude the consultation being rescheduled)
        $query = Consultation::where('lawyer_id', $lawyer->id)
            ->whereIn('status', ['scheduled', 'in_progress', 'payment_pending', 'payment_processing', 'accepted'])
            ->whereDate('scheduled_at', $date->format('Y-m-d'));
        
        if ($excludeConsultationId) {
            $query->where('id', '!=', $excludeConsultationId);
        }
        
        $existingConsultations = $query->get(['scheduled_at', 'duration']);

        // Generate time slots (30-minute intervals)
        $slots = [];
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $availabilitySchedule->start_time);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $availabilitySchedule->end_time);

        $currentSlot = $startTime->copy();

        while ($currentSlot->copy()->addMinutes($duration)->lte($endTime)) {
            // Check if slot is in the past
            if ($currentSlot->isPast()) {
                $currentSlot->addMinutes(30);
                continue;
            }

            // For reschedule, allow slots with shorter notice (minimum 2 hours instead of 24)
            if ($currentSlot->lessThanOrEqualTo(now()->addHours(2))) {
                $currentSlot->addMinutes(30);
                continue;
            }

            // Check for conflicts with existing consultations
            $hasConflict = false;
            $slotEnd = $currentSlot->copy()->addMinutes($duration);

            foreach ($existingConsultations as $consultation) {
                $consultationStart = Carbon::parse($consultation->scheduled_at);
                $consultationEnd = $consultationStart->copy()->addMinutes($consultation->duration);

                // Check if slots overlap
                if ($currentSlot->lt($consultationEnd) && $slotEnd->gt($consultationStart)) {
                    $hasConflict = true;
                    break;
                }
            }

            if (!$hasConflict) {
                \Log::info('Slot available', [
                    'time' => $currentSlot->format('H:i'),
                    'datetime' => $currentSlot->toDateTimeString(),
                ]);
                
                $slots[] = [
                    'time' => $currentSlot->format('H:i'),
                    'display' => $currentSlot->format('g:i A'),
                    'datetime' => $currentSlot->toDateTimeString(),
                ];
            } else {
                \Log::info('Slot has conflict', [
                    'time' => $currentSlot->format('H:i'),
                ]);
            }

            $currentSlot->addMinutes(30);
        }

        \Log::info('getAvailableTimeSlots result', [
            'total_slots' => count($slots),
            'slots' => array_column($slots, 'display'),
        ]);

        return $slots;
    }
}
