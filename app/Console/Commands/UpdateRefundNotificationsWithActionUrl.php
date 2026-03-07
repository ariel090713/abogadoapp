<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateRefundNotificationsWithActionUrl extends Command
{
    protected $signature = 'notifications:update-refund-action-urls';
    protected $description = 'Update existing refund notifications with action_url';

    public function handle()
    {
        $this->info('Updating refund notifications with action URLs...');

        // Update RefundRequestForLawyer notifications
        $updated = DB::table('notifications')
            ->where('type', 'App\\Notifications\\RefundRequestForLawyer')
            ->whereNull('read_at')
            ->get()
            ->each(function ($notification) {
                $data = json_decode($notification->data, true);
                if (!isset($data['action_url']) && isset($data['transaction_id'])) {
                    $data['action_url'] = route('lawyer.transaction.details', $data['transaction_id']);
                    DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update(['data' => json_encode($data)]);
                    $this->info("Updated notification {$notification->id}");
                }
            });

        // Update other refund notifications for clients
        $clientNotifications = [
            'App\\Notifications\\RefundRequestReceived',
            'App\\Notifications\\LawyerApprovedRefund',
            'App\\Notifications\\LawyerRejectedRefund',
            'App\\Notifications\\RefundApproved',
            'App\\Notifications\\RefundRejected',
            'App\\Notifications\\RefundCompleted',
        ];

        foreach ($clientNotifications as $notificationType) {
            DB::table('notifications')
                ->where('type', $notificationType)
                ->whereNull('read_at')
                ->get()
                ->each(function ($notification) {
                    $data = json_decode($notification->data, true);
                    if (!isset($data['action_url']) && isset($data['transaction_id'])) {
                        $data['action_url'] = route('client.transactions.details', $data['transaction_id']);
                        DB::table('notifications')
                            ->where('id', $notification->id)
                            ->update(['data' => json_encode($data)]);
                        $this->info("Updated notification {$notification->id}");
                    }
                });
        }

        $this->info('Done!');
        return 0;
    }
}
