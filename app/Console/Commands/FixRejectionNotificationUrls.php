<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRejectionNotificationUrls extends Command
{
    protected $signature = 'notifications:fix-rejection-urls';
    protected $description = 'Fix action URLs in LawyerRejected notifications';

    public function handle()
    {
        $correctUrl = route('lawyer.profile.professional');
        
        $notifications = DB::table('notifications')
            ->where('type', 'App\\Notifications\\LawyerRejected')
            ->get();
        
        $updated = 0;
        
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            
            if (isset($data['action_url']) && $data['action_url'] !== $correctUrl) {
                $data['action_url'] = $correctUrl;
                
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data)]);
                
                $updated++;
            }
        }
        
        $this->info("Updated {$updated} notification(s)");
        
        return 0;
    }
}
