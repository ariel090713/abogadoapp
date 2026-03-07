<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileNotifications extends Component
{
    public $notif_consultation_updates_mail = true;
    public $notif_consultation_updates_database = true;
    public $notif_consultation_updates_broadcast = true;
    public $notif_payment_updates_mail = true;
    public $notif_payment_updates_database = true;
    public $notif_payment_updates_broadcast = true;
    public $notif_marketing_mail = true;

    public function mount()
    {
        $user = Auth::user();
        $prefs = $user->notification_preferences ?? [];
        
        $this->notif_consultation_updates_mail = $prefs['consultation_updates']['mail'] ?? true;
        $this->notif_consultation_updates_database = $prefs['consultation_updates']['database'] ?? true;
        $this->notif_consultation_updates_broadcast = $prefs['consultation_updates']['broadcast'] ?? true;
        $this->notif_payment_updates_mail = $prefs['payment_updates']['mail'] ?? true;
        $this->notif_payment_updates_database = $prefs['payment_updates']['database'] ?? true;
        $this->notif_payment_updates_broadcast = $prefs['payment_updates']['broadcast'] ?? true;
        $this->notif_marketing_mail = $prefs['system_updates']['mail'] ?? true;
    }

    public function updatedNotifConsultationUpdatesMail()
    {
        $this->saveNotificationPreferences();
    }

    public function updatedNotifPaymentUpdatesMail()
    {
        $this->saveNotificationPreferences();
    }

    public function updatedNotifMarketingMail()
    {
        $this->saveNotificationPreferences();
    }

    protected function saveNotificationPreferences()
    {
        try {
            $user = Auth::user();
            $user->update([
                'notification_preferences' => [
                    'consultation_updates' => [
                        'mail' => $this->notif_consultation_updates_mail,
                        'database' => $this->notif_consultation_updates_database,
                        'broadcast' => $this->notif_consultation_updates_broadcast,
                    ],
                    'payment_updates' => [
                        'mail' => $this->notif_payment_updates_mail,
                        'database' => $this->notif_payment_updates_database,
                        'broadcast' => $this->notif_payment_updates_broadcast,
                    ],
                    'system_updates' => [
                        'mail' => $this->notif_marketing_mail,
                        'database' => true,
                        'broadcast' => false,
                    ],
                ],
            ]);

            session()->flash('success', 'Notification preferences updated!');
        } catch (\Exception $e) {
            \Log::error('Failed to save notification preferences', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.client.profile-notifications')
            ->layout('layouts.dashboard', ['title' => 'Notifications']);
    }
}
