<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixNotificationUrls extends Command
{
    protected $signature = 'notifications:fix-urls';
    protected $description = 'Fix missing action_url in all notifications';

    public function handle()
    {
        $notifications = DB::table('notifications')->get();
        
        $updated = 0;
        
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            $needsUpdate = false;
            
            // If action_url is missing or is "#", try to fix it
            if (!isset($data['action_url']) || $data['action_url'] === '#') {
                
                // Document request notifications - use 'url' field if available
                if (isset($data['url']) && $data['url'] !== '#') {
                    $data['action_url'] = $data['url'];
                    $needsUpdate = true;
                }
                
                // Service declined/accepted notifications - use consultation_id
                elseif (isset($data['consultation_id']) && isset($data['parent_case_id'])) {
                    $data['action_url'] = url('/lawyer/consultation-threads/' . $data['parent_case_id']);
                    $needsUpdate = true;
                }
                
                // Generic fallback based on notification type
                elseif (isset($data['type'])) {
                    $url = $this->getUrlForType($data['type'], $data);
                    if ($url) {
                        $data['action_url'] = $url;
                        $needsUpdate = true;
                    }
                }
            }
            
            if ($needsUpdate) {
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data)]);
                
                $updated++;
            }
        }
        
        $this->info("Updated {$updated} notification(s)");
        
        return 0;
    }
    
    private function getUrlForType($type, $data)
    {
        return match($type) {
            'document_request_received',
            'document_revision_requested' => isset($data['request_id']) 
                ? url('/lawyer/document-requests/' . $data['request_id']) 
                : null,
            
            'payment_received',
            'payment_successful',
            'consultation_cancelled' => isset($data['consultation_id']) 
                ? url('/lawyer/consultations/' . $data['consultation_id']) 
                : null,
            
            default => null,
        };
    }
}
