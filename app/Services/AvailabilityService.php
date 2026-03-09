<?php

namespace App\Services;

use App\Models\LawyerProfile;
use App\Models\Consultation;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Check if lawyer is available at the given date and time
     */
    public function isAvailable(LawyerProfile $lawyer, Carbon $dateTime, int $duration): bool
    {
        // Check if it's within lawyer's weekly schedule
        if (!$this->isWithinSchedule($lawyer, $dateTime)) {
            return false;
        }

        // Check if there's no conflicting consultation
        if ($this->hasConflictingConsultation($lawyer, $dateTime, $duration)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the datetime falls within lawyer's weekly schedule
     */
    private function isWithinSchedule(LawyerProfile $lawyer, Carbon $dateTime): bool
    {
        $dayOfWeek = $dateTime->dayOfWeekIso; // 1 = Monday, 7 = Sunday
        
        $schedule = $lawyer->availabilitySchedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            return false;
        }

        $requestTime = $dateTime->format('H:i:s');
        
        return $requestTime >= $schedule->start_time && $requestTime <= $schedule->end_time;
    }

    /**
     * Check if there's a conflicting consultation
     */
    private function hasConflictingConsultation(LawyerProfile $lawyer, Carbon $dateTime, int $duration): bool
    {
        // Add 15-minute buffer after consultation for lawyer's break time
        $bufferMinutes = 15;
        $endTime = $dateTime->copy()->addMinutes($duration);
        $endTimeWithBuffer = $endTime->copy()->addMinutes($bufferMinutes);

        // Check for conflicting consultations
        // Include all statuses that represent active/upcoming bookings
        $conflictingConsultations = Consultation::where('lawyer_id', $lawyer->user_id)
            ->whereIn('status', [
                'pending',                    // Waiting for lawyer approval
                'accepted',                   // Lawyer accepted, waiting for payment
                'payment_pending',            // Waiting for payment
                'payment_processing',         // Payment being processed
                'scheduled',                  // Paid and scheduled
                'awaiting_quote_approval',    // Document review quote sent
                'in_progress'                 // Currently ongoing
            ])
            ->whereNotNull('scheduled_at')
            ->where(function ($query) use ($dateTime, $endTimeWithBuffer, $bufferMinutes) {
                // Check if requested time conflicts with existing consultation + buffer
                // Existing consultation end time (with buffer) must be before requested start
                // OR existing consultation start time must be after requested end (with buffer)
                $query->where(function ($q) use ($dateTime, $endTimeWithBuffer, $bufferMinutes) {
                    // Requested time starts before existing consultation ends (with buffer)
                    $q->where('scheduled_at', '<', $endTimeWithBuffer)
                      ->whereRaw('DATE_ADD(scheduled_at, INTERVAL (duration + ?) MINUTE) > ?', [$bufferMinutes, $dateTime]);
                });
            })
            ->exists();
        
        if ($conflictingConsultations) {
            return true;
        }
        
        // Check for blocked dates
        $blockedDate = $lawyer->blockedDates()
            ->where('blocked_date', $dateTime->format('Y-m-d'))
            ->first();
        
        if ($blockedDate) {
            // If full day block
            if ($blockedDate->is_full_day) {
                return true;
            }
            
            // If partial block, check time overlap
            $blockStart = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $blockedDate->start_time);
            $blockEnd = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $blockedDate->end_time);
            
            // Check if times overlap: block starts before requested ends AND block ends after requested starts
            if ($blockStart < $endTime && $blockEnd > $dateTime) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots(LawyerProfile $lawyer, Carbon $date, int $duration = 15): array
    {
        $dayOfWeek = $date->dayOfWeekIso;
        
        $schedule = $lawyer->availabilitySchedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            return [];
        }

        $slots = [];
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);

        $currentSlot = $startTime->copy();

        while ($currentSlot->copy()->addMinutes($duration) <= $endTime) {
            // Skip past times
            if ($currentSlot->isPast()) {
                $currentSlot->addMinutes($duration);
                continue;
            }

            // Check if slot is available
            if ($this->isAvailable($lawyer, $currentSlot, $duration)) {
                $slots[] = [
                    'time' => $currentSlot->format('H:i'),
                    'datetime' => $currentSlot->toDateTimeString(),
                    'formatted' => $currentSlot->format('g:i A'),
                ];
            }

            $currentSlot->addMinutes($duration);
        }

        return $slots;
    }

    /**
     * Get next available date for lawyer
     */
    public function getNextAvailableDate(LawyerProfile $lawyer, int $duration = 15): ?Carbon
    {
        $date = Carbon::today();
        $maxDays = 30; // Look ahead 30 days

        for ($i = 0; $i < $maxDays; $i++) {
            $slots = $this->getAvailableSlots($lawyer, $date, $duration);
            
            if (!empty($slots)) {
                return $date;
            }

            $date->addDay();
        }

        return null;
    }
}
