<?php

namespace App\Console\Commands;

use App\Models\Consultation;
use App\Notifications\ConsultationCancelled;
use App\Notifications\ConsultationDeclined;
use App\Notifications\ConsultationReminder;
use App\Notifications\ConsultationStarting;
use App\Notifications\QuoteDeclined;
use App\Services\RefundService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessConsultationWorkflow extends Command
{
    protected $signature = 'consultations:process';
    protected $description = 'Process all consultation workflows (deadlines, reminders, status updates)';

    public function __construct(
        private RefundService $refundService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Processing consultation workflow...');

        // Process all deadline expirations
        $this->expirePendingConsultations();
        $this->expireQuoteApprovals();
        $this->expirePaymentDeadlines();
        
        // Update consultation statuses based on time
        $this->updateConsultationStatuses();
        
        // Send reminders
        $this->sendUpcomingReminders();

        $this->info('Consultation workflow processed successfully!');
    }

    /**
     * Expire pending consultations that passed lawyer response deadline
     */
    private function expirePendingConsultations()
    {
        $expired = Consultation::where('status', 'pending')
            ->whereNotNull('lawyer_response_deadline')
            ->where('lawyer_response_deadline', '<=', now())
            ->get();

        foreach ($expired as $consultation) {
            $consultation->update([
                'status' => 'expired',
                'decline_reason' => 'Lawyer did not respond within the deadline.',
            ]);
            
            // Create automatic refund if payment was made
            if ($consultation->transaction && in_array($consultation->transaction->status, ['completed', 'captured'])) {
                try {
                    $this->refundService->createAutoRefund(
                        $consultation->transaction,
                        'expired_lawyer_response',
                        'Lawyer did not respond within the 24-hour deadline. Full refund issued automatically.'
                    );
                    
                    Log::info('Auto-refund created for expired consultation', [
                        'consultation_id' => $consultation->id,
                        'transaction_id' => $consultation->transaction->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create auto-refund for expired consultation', [
                        'consultation_id' => $consultation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            $consultation->client->notify(new ConsultationDeclined($consultation));
            
            Log::info('Consultation expired - lawyer response deadline', [
                'consultation_id' => $consultation->id,
                'deadline' => $consultation->lawyer_response_deadline,
            ]);
        }

        if ($expired->count() > 0) {
            $this->info("Expired {$expired->count()} pending consultation(s) - lawyer response deadline");
        }
    }

    /**
     * Expire quote approvals that passed quote deadline
     */
    private function expireQuoteApprovals()
    {
        $expired = Consultation::where('status', 'awaiting_quote_approval')
            ->whereNotNull('quote_deadline')
            ->where('quote_deadline', '<=', now())
            ->get();

        foreach ($expired as $consultation) {
            $consultation->update([
                'status' => 'expired',
                'decline_reason' => 'Client did not respond to quote within the deadline.',
            ]);
            
            // Create automatic refund if payment was made
            if ($consultation->transaction && in_array($consultation->transaction->status, ['completed', 'captured'])) {
                try {
                    $this->refundService->createAutoRefund(
                        $consultation->transaction,
                        'expired_quote',
                        'Client did not respond to quote within the 24-hour deadline. Full refund issued automatically.'
                    );
                    
                    Log::info('Auto-refund created for expired quote approval', [
                        'consultation_id' => $consultation->id,
                        'transaction_id' => $consultation->transaction->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create auto-refund for expired quote', [
                        'consultation_id' => $consultation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            $consultation->lawyer->notify(new QuoteDeclined($consultation));
            
            Log::info('Consultation expired - quote response deadline', [
                'consultation_id' => $consultation->id,
                'deadline' => $consultation->quote_deadline,
            ]);
        }

        if ($expired->count() > 0) {
            $this->info("Expired {$expired->count()} quote approval(s) - client response deadline");
        }
    }

    /**
     * Expire payment pending consultations that passed payment deadline
     */
    private function expirePaymentDeadlines()
    {
        $expired = Consultation::where('status', 'payment_pending')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<=', now())
            ->get();

        foreach ($expired as $consultation) {
            $consultation->update([
                'status' => 'cancelled',
                'cancel_reason' => 'Payment deadline expired. Payment was not completed within the deadline.',
            ]);
            
            // Create automatic refund if payment was made
            if ($consultation->transaction && in_array($consultation->transaction->status, ['completed', 'captured'])) {
                try {
                    $this->refundService->createAutoRefund(
                        $consultation->transaction,
                        'expired_payment',
                        'Payment deadline expired. Full refund issued automatically.'
                    );
                    
                    Log::info('Auto-refund created for expired payment deadline', [
                        'consultation_id' => $consultation->id,
                        'transaction_id' => $consultation->transaction->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create auto-refund for expired payment', [
                        'consultation_id' => $consultation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            try {
                $consultation->client->notify(new ConsultationCancelled($consultation));
                $consultation->lawyer->notify(new ConsultationCancelled($consultation));
            } catch (\Exception $e) {
                Log::error('Failed to send cancellation notification', [
                    'consultation_id' => $consultation->id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            Log::info('Consultation cancelled - payment deadline expired', [
                'consultation_id' => $consultation->id,
                'deadline' => $consultation->payment_deadline,
            ]);
        }

        if ($expired->count() > 0) {
            $this->info("Cancelled {$expired->count()} consultation(s) - payment deadline expired");
        }
    }

    /**
     * Update consultation statuses based on time
     */
    private function updateConsultationStatuses()
    {
        // Update scheduled consultations to in_progress if time has started (even if already ended)
        $starting = Consultation::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($starting as $consultation) {
            $consultation->update([
                'status' => 'in_progress',
                'started_at' => $consultation->started_at ?? now(),
            ]);

            Log::info('Consultation status updated to in_progress', [
                'consultation_id' => $consultation->id,
            ]);
        }

        // Mark consultations as ended if time has passed (only if not yet marked)
        $ending = Consultation::whereIn('status', ['scheduled', 'in_progress'])
            ->whereNull('ended_at')  // Only process if not yet marked as ended
            ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration MINUTE) <= ?', [now()])
            ->get();

        foreach ($ending as $consultation) {
            $endTime = $consultation->scheduled_at->copy()->addMinutes($consultation->duration);
            
            $consultation->update([
                'ended_at' => $endTime,
            ]);

            Log::info('Consultation marked as ended', [
                'consultation_id' => $consultation->id,
                'ended_at' => $endTime,
            ]);
        }

        if ($starting->count() > 0 || $ending->count() > 0) {
            $this->info("Updated {$starting->count()} to in_progress, {$ending->count()} marked as ended");
        }
    }

    /**
     * Send reminders for upcoming consultations
     */
    private function sendUpcomingReminders()
    {
        $totalReminders = 0;

        // 24 hour reminder
        $tomorrow = Consultation::where('status', 'scheduled')
            ->whereBetween('scheduled_at', [
                now()->addHours(23),
                now()->addHours(25)
            ])
            ->whereNull('reminder_24h_sent_at')
            ->get();

        foreach ($tomorrow as $consultation) {
            $consultation->client->notify(new ConsultationReminder($consultation, '24 hours'));
            $consultation->lawyer->notify(new ConsultationReminder($consultation, '24 hours'));
            
            $consultation->update(['reminder_24h_sent_at' => now()]);
            $totalReminders++;
        }

        // 1 hour reminder
        $oneHour = Consultation::where('status', 'scheduled')
            ->whereBetween('scheduled_at', [
                now()->addMinutes(55),
                now()->addMinutes(65)
            ])
            ->whereNull('reminder_1h_sent_at')
            ->get();

        foreach ($oneHour as $consultation) {
            $consultation->client->notify(new ConsultationReminder($consultation, '1 hour'));
            $consultation->lawyer->notify(new ConsultationReminder($consultation, '1 hour'));
            
            $consultation->update(['reminder_1h_sent_at' => now()]);
            $totalReminders++;
        }

        // 15 minute reminder (consultation starting soon)
        $fifteenMin = Consultation::where('status', 'scheduled')
            ->whereBetween('scheduled_at', [
                now()->addMinutes(10),
                now()->addMinutes(20)
            ])
            ->whereNull('reminder_15m_sent_at')
            ->get();

        foreach ($fifteenMin as $consultation) {
            $consultation->client->notify(new ConsultationStarting($consultation));
            $consultation->lawyer->notify(new ConsultationStarting($consultation));
            
            $consultation->update(['reminder_15m_sent_at' => now()]);
            $totalReminders++;
        }

        if ($totalReminders > 0) {
            $this->info("Sent {$totalReminders} reminder(s)");
        }
    }
}
