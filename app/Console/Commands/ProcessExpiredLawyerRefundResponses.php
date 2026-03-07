<?php

namespace App\Console\Commands;

use App\Models\Refund;
use App\Notifications\LawyerApprovedRefund;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessExpiredLawyerRefundResponses extends Command
{
    protected $signature = 'refunds:process-expired-lawyer-responses';
    protected $description = 'Auto-approve refunds where lawyer did not respond within 7 days';

    public function handle()
    {
        $this->info('Processing expired lawyer refund responses...');

        // Find refunds where:
        // 1. Lawyer approval status is still pending
        // 2. Deadline has passed
        // 3. Refund is not rejected by admin
        $expiredRefunds = Refund::where('lawyer_approval_status', 'pending')
            ->where('lawyer_response_deadline', '<=', now())
            ->where('status', '!=', 'rejected')
            ->whereNotNull('lawyer_id')
            ->get();

        if ($expiredRefunds->isEmpty()) {
            $this->info('No expired lawyer responses found.');
            return 0;
        }

        $count = 0;
        foreach ($expiredRefunds as $refund) {
            try {
                // Auto-approve lawyer response
                $refund->update([
                    'lawyer_approval_status' => 'approved',
                    'lawyer_notes' => 'Auto-approved: Lawyer did not respond within 7 days.',
                    'lawyer_responded_at' => now(),
                ]);

                // Notify client
                $refund->user->notify(new LawyerApprovedRefund($refund));

                Log::info('Auto-approved refund due to expired lawyer response', [
                    'refund_id' => $refund->id,
                    'lawyer_id' => $refund->lawyer_id,
                    'deadline' => $refund->lawyer_response_deadline,
                ]);

                $count++;
                $this->info("Auto-approved refund #{$refund->id}");

            } catch (\Exception $e) {
                Log::error('Failed to auto-approve expired refund', [
                    'refund_id' => $refund->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Failed to process refund #{$refund->id}: " . $e->getMessage());
            }
        }

        $this->info("Processed {$count} expired lawyer responses.");
        return 0;
    }
}
