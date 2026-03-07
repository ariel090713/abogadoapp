<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRefundNotificationRoutes extends Command
{
    protected $signature = 'refund:fix-notification-routes';
    protected $description = 'Fix incorrect route names in refund notifications';

    public function handle()
    {
        $this->info('Fixing notification routes...');

        $notifications = DB::table('notifications')->get();
        $fixed = 0;

        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            $updated = false;

            if (isset($data['action_url'])) {
                // Fix client routes
                if (str_contains($data['action_url'], 'client.transaction.details')) {
                    $data['action_url'] = str_replace('client.transaction.details', 'client.transactions.details', $data['action_url']);
                    $updated = true;
                }

                // Fix lawyer routes
                if (str_contains($data['action_url'], 'lawyer.transaction.details')) {
                    $data['action_url'] = str_replace('lawyer.transaction.details', 'lawyer.transactions.details', $data['action_url']);
                    $updated = true;
                }

                if ($updated) {
                    DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update(['data' => json_encode($data)]);
                    $fixed++;
                    $this->info("Fixed notification {$notification->id}");
                }
            }
        }

        $this->info("Fixed {$fixed} notifications.");
        return 0;
    }
}
