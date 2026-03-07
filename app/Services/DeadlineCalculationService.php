<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeadlineCalculationService
{
    /**
     * Get setting value with fallback to constant
     */
    private function getSetting(string $key, $fallback)
    {
        return SystemSetting::get($key, $fallback);
    }

    /**
     * Get minimum advance booking hours
     */
    private function getMinAdvanceBookingHours(): int
    {
        return $this->getSetting('deadline.video_min_advance_booking_hours', 3);
    }

    /**
     * Get lawyer response settings
     */
    private function getLawyerResponseHours(string $type): int
    {
        $key = $type === 'document_review' 
            ? 'deadline.document_lawyer_response_hours'
            : 'deadline.video_lawyer_response_hours';
        
        return $this->getSetting($key, $type === 'document_review' ? 48 : 24);
    }

    private function getLawyerResponseBufferHours(): int
    {
        return $this->getSetting('deadline.video_lawyer_response_buffer_hours', 2);
    }

    /**
     * Get quote response settings
     */
    private function getQuoteResponseHours(string $type): int
    {
        $key = $type === 'document_review'
            ? 'deadline.document_quote_response_hours'
            : 'deadline.video_quote_response_hours';
        
        return $this->getSetting($key, $type === 'document_review' ? 48 : 24);
    }

    private function getQuoteResponseBufferHours(): int
    {
        return $this->getSetting('deadline.video_quote_response_buffer_hours', 1);
    }

    /**
     * Get payment settings
     */
    private function getPaymentHours(string $type): int
    {
        $key = $type === 'document_review'
            ? 'deadline.document_payment_hours'
            : 'deadline.video_payment_hours';
        
        return $this->getSetting($key, $type === 'document_review' ? 24 : 24);
    }

    private function getPaymentBufferHours(): int
    {
        return $this->getSetting('deadline.video_payment_buffer_hours', 1);
    }

    /**
     * Validate if booking time is acceptable
     */
    public function validateBookingTime(Carbon $scheduledAt, string $consultationType): array
    {
        // Document reviews don't need scheduled time
        if ($consultationType === 'document_review') {
            return ['valid' => true];
        }

        $now = now();
        $hoursUntilSession = $now->diffInHours($scheduledAt, false);
        $minHours = $this->getMinAdvanceBookingHours();

        // Check minimum advance booking
        if ($hoursUntilSession < $minHours) {
            return [
                'valid' => false,
                'message' => sprintf(
                    'Booking must be at least %d hours in advance. Please select a time after %s',
                    $minHours,
                    $now->addHours($minHours)->format('M d, Y g:i A')
                ),
                'min_time' => $now->addHours($minHours),
            ];
        }

        return ['valid' => true];
    }

    /**
     * Calculate lawyer response deadline
     */
    public function calculateLawyerResponseDeadline(Consultation $consultation): Carbon
    {
        $createdAt = $consultation->created_at;
        $type = $consultation->consultation_type;
        
        // Document review: configurable hours
        if ($type === 'document_review') {
            $hours = $this->getLawyerResponseHours($type);
            return Carbon::parse($createdAt)->addHours($hours);
        }

        // Video/Phone/Chat: standard hours OR buffer before session
        $standardHours = $this->getLawyerResponseHours($type);
        $bufferHours = $this->getLawyerResponseBufferHours();
        
        $standardDeadline = Carbon::parse($createdAt)->addHours($standardHours);
        $sessionBufferDeadline = Carbon::parse($consultation->scheduled_at)->subHours($bufferHours);

        // Use whichever comes first
        $deadline = $standardDeadline->lt($sessionBufferDeadline) 
            ? $standardDeadline 
            : $sessionBufferDeadline;

        Log::info('Lawyer response deadline calculated', [
            'consultation_id' => $consultation->id,
            'created_at' => $createdAt->toDateTimeString(),
            'scheduled_at' => $consultation->scheduled_at?->toDateTimeString(),
            'standard_deadline' => $standardDeadline->toDateTimeString(),
            'session_buffer_deadline' => $sessionBufferDeadline->toDateTimeString(),
            'final_deadline' => $deadline->toDateTimeString(),
        ]);

        return $deadline;
    }

    /**
     * Calculate quote response deadline
     */
    public function calculateQuoteResponseDeadline(Consultation $consultation): Carbon
    {
        $quoteProvidedAt = $consultation->quote_provided_at ?? now();
        $type = $consultation->consultation_type;
        
        // Document review: configurable hours
        if ($type === 'document_review') {
            $hours = $this->getQuoteResponseHours($type);
            return Carbon::parse($quoteProvidedAt)->addHours($hours);
        }

        // Video/Phone/Chat: standard hours OR buffer before session
        $standardHours = $this->getQuoteResponseHours($type);
        $bufferHours = $this->getQuoteResponseBufferHours();
        
        $standardDeadline = Carbon::parse($quoteProvidedAt)->addHours($standardHours);
        $sessionBufferDeadline = Carbon::parse($consultation->scheduled_at)->subHours($bufferHours);

        // Use whichever comes first
        $deadline = $standardDeadline->lt($sessionBufferDeadline) 
            ? $standardDeadline 
            : $sessionBufferDeadline;

        Log::info('Quote response deadline calculated', [
            'consultation_id' => $consultation->id,
            'quote_provided_at' => $quoteProvidedAt->toDateTimeString(),
            'scheduled_at' => $consultation->scheduled_at?->toDateTimeString(),
            'standard_deadline' => $standardDeadline->toDateTimeString(),
            'session_buffer_deadline' => $sessionBufferDeadline->toDateTimeString(),
            'final_deadline' => $deadline->toDateTimeString(),
        ]);

        return $deadline;
    }

    /**
     * Calculate payment deadline
     */
    public function calculatePaymentDeadline(Consultation $consultation): Carbon
    {
        $acceptedAt = $consultation->accepted_at ?? $consultation->quote_accepted_at ?? now();
        $type = $consultation->consultation_type;
        
        // Document review: configurable hours
        if ($type === 'document_review') {
            $hours = $this->getPaymentHours($type);
            return Carbon::parse($acceptedAt)->addHours($hours);
        }

        // Video/Phone/Chat: standard hours OR buffer before session
        $standardHours = $this->getPaymentHours($type);
        $bufferHours = $this->getPaymentBufferHours();
        
        $standardDeadline = Carbon::parse($acceptedAt)->addHours($standardHours);
        $sessionBufferDeadline = Carbon::parse($consultation->scheduled_at)->subHours($bufferHours);

        // Use whichever comes first
        $deadline = $standardDeadline->lt($sessionBufferDeadline) 
            ? $standardDeadline 
            : $sessionBufferDeadline;

        Log::info('Payment deadline calculated', [
            'consultation_id' => $consultation->id,
            'accepted_at' => $acceptedAt->toDateTimeString(),
            'scheduled_at' => $consultation->scheduled_at?->toDateTimeString(),
            'standard_deadline' => $standardDeadline->toDateTimeString(),
            'session_buffer_deadline' => $sessionBufferDeadline->toDateTimeString(),
            'final_deadline' => $deadline->toDateTimeString(),
        ]);

        return $deadline;
    }

    /**
     * Check if there's enough time for the full flow
     */
    public function hasEnoughTimeForFullFlow(Carbon $scheduledAt): array
    {
        $now = now();
        $hoursUntilSession = $now->diffInHours($scheduledAt, false);

        // Calculate minimum required time from settings
        $minLawyerResponse = $this->getLawyerResponseBufferHours();
        $minQuoteResponse = $this->getQuoteResponseBufferHours();
        $minPayment = $this->getPaymentBufferHours();
        
        $totalMinRequired = $minLawyerResponse + $minQuoteResponse + $minPayment;

        if ($hoursUntilSession < $totalMinRequired) {
            return [
                'enough_time' => false,
                'hours_until_session' => $hoursUntilSession,
                'min_required_hours' => $totalMinRequired,
                'message' => sprintf(
                    'Not enough time for full flow. Session is in %.1f hours but minimum %.1f hours required.',
                    $hoursUntilSession,
                    $totalMinRequired
                ),
            ];
        }

        return [
            'enough_time' => true,
            'hours_until_session' => $hoursUntilSession,
            'min_required_hours' => $totalMinRequired,
            'buffer_hours' => $hoursUntilSession - $totalMinRequired,
        ];
    }

    /**
     * Get time remaining until deadline
     */
    public function getTimeRemaining(Carbon $deadline): array
    {
        $now = now();
        
        if ($deadline->isPast()) {
            return [
                'expired' => true,
                'total_seconds' => 0,
                'formatted' => 'Expired',
                'urgency' => 'expired',
            ];
        }

        $diff = $now->diff($deadline);
        $totalSeconds = $now->diffInSeconds($deadline);
        $totalHours = $now->diffInHours($deadline);
        
        // Format time remaining
        if ($totalHours >= 24) {
            $formatted = sprintf('%d days %d hours', $diff->d, $diff->h);
        } elseif ($totalHours >= 1) {
            $formatted = sprintf('%d hours %d minutes', $diff->h, $diff->i);
        } elseif ($diff->i >= 1) {
            $formatted = sprintf('%d minutes', $diff->i);
        } else {
            $formatted = sprintf('%d seconds', $diff->s);
        }

        // Determine urgency level
        $percentRemaining = ($totalSeconds / (24 * 3600)) * 100; // Assuming 24hr deadline
        
        if ($percentRemaining > 50) {
            $urgency = 'safe';
        } elseif ($percentRemaining > 25) {
            $urgency = 'warning';
        } else {
            $urgency = 'urgent';
        }

        return [
            'expired' => false,
            'total_seconds' => $totalSeconds,
            'total_hours' => $totalHours,
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'formatted' => $formatted,
            'urgency' => $urgency,
            'percent_remaining' => min(100, $percentRemaining),
        ];
    }

    /**
     * Calculate all deadlines for a consultation
     */
    public function calculateAllDeadlines(Consultation $consultation): array
    {
        $deadlines = [];

        // Lawyer response deadline
        if ($consultation->status === 'pending') {
            $deadlines['lawyer_response'] = [
                'deadline' => $this->calculateLawyerResponseDeadline($consultation),
                'type' => 'lawyer_response',
                'label' => 'Lawyer Response Deadline',
            ];
        }

        // Quote response deadline
        if ($consultation->status === 'awaiting_quote_approval') {
            $deadlines['quote_response'] = [
                'deadline' => $this->calculateQuoteResponseDeadline($consultation),
                'type' => 'quote_response',
                'label' => 'Quote Response Deadline',
            ];
        }

        // Payment deadline
        if ($consultation->status === 'payment_pending') {
            $deadlines['payment'] = [
                'deadline' => $this->calculatePaymentDeadline($consultation),
                'type' => 'payment',
                'label' => 'Payment Deadline',
            ];
        }

        // Review completion deadline (for document reviews)
        if ($consultation->consultation_type === 'document_review' && 
            in_array($consultation->status, ['scheduled', 'in_progress']) &&
            $consultation->review_completion_deadline) {
            $deadlines['review_completion'] = [
                'deadline' => $consultation->review_completion_deadline,
                'type' => 'review_completion',
                'label' => 'Review Completion Deadline',
            ];
        }

        // Add time remaining for each deadline
        foreach ($deadlines as $key => $deadline) {
            $deadlines[$key]['time_remaining'] = $this->getTimeRemaining($deadline['deadline']);
        }

        return $deadlines;
    }

    /**
     * Calculate review completion deadline based on estimated turnaround days
     */
    public function calculateReviewCompletionDeadline(Consultation $consultation): Carbon
    {
        $startDate = $consultation->transaction?->processed_at ?? now();
        $turnaroundDays = $consultation->estimated_turnaround_days ?? 3; // Default 3 days
        
        return Carbon::parse($startDate)->addDays($turnaroundDays);
    }

    /**
     * Validate if lawyer can still accept (enough time remaining)
     */
    public function canLawyerAccept(Consultation $consultation): array
    {
        // Document reviews always have enough time
        if ($consultation->consultation_type === 'document_review') {
            return ['can_accept' => true];
        }

        $now = now();
        $hoursUntilSession = $now->diffInHours($consultation->scheduled_at, false);

        // Need at least buffer time for quote + payment
        $minRequired = $this->getQuoteResponseBufferHours() + $this->getPaymentBufferHours();

        if ($hoursUntilSession < $minRequired) {
            return [
                'can_accept' => false,
                'reason' => sprintf(
                    'Not enough time remaining. Session is in %.1f hours but minimum %.1f hours required for quote and payment.',
                    $hoursUntilSession,
                    $minRequired
                ),
            ];
        }

        return ['can_accept' => true];
    }

    /**
     * Validate if client can still pay (enough time before session)
     */
    public function canClientPay(Consultation $consultation): array
    {
        // Document reviews always have enough time
        if ($consultation->consultation_type === 'document_review') {
            return ['can_pay' => true];
        }

        $now = now();
        $hoursUntilSession = $now->diffInHours($consultation->scheduled_at, false);
        $minRequired = $this->getPaymentBufferHours();

        // Need at least buffer time before session
        if ($hoursUntilSession < $minRequired) {
            return [
                'can_pay' => false,
                'reason' => sprintf(
                    'Too close to session time. Session is in %.1f hours but minimum %.1f hour buffer required.',
                    $hoursUntilSession,
                    $minRequired
                ),
            ];
        }

        return ['can_pay' => true];
    }

    /**
     * Calculate document completion deadline (business days)
     */
    public function calculateDocumentCompletionDeadline(int $estimatedDays): Carbon
    {
        $deadline = Carbon::now();
        $daysAdded = 0;

        while ($daysAdded < $estimatedDays) {
            $deadline->addDay();
            
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($deadline->dayOfWeek !== Carbon::SATURDAY && $deadline->dayOfWeek !== Carbon::SUNDAY) {
                $daysAdded++;
            }
        }

        // Set to end of business day (5 PM)
        $deadline->setTime(17, 0, 0);

        Log::info('Document completion deadline calculated', [
            'estimated_days' => $estimatedDays,
            'deadline' => $deadline->toDateTimeString(),
        ]);

        return $deadline;
    }
}
