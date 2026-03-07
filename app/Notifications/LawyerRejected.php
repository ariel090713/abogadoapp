<?php

namespace App\Notifications;

use App\Models\LawyerProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LawyerRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public LawyerProfile $lawyerProfile,
        public string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update on Your Lawyer Profile Application')
            ->greeting('Hello, ' . $notifiable->name)
            ->line('Thank you for your interest in joining AbogadoMo as a verified lawyer.')
            ->line('Unfortunately, we are unable to verify your profile at this time.')
            ->line('Reason: ' . $this->reason)
            ->line('If you believe this is an error or would like to resubmit your application with updated information, please contact our support team.')
            ->action('Contact Support', route('contact'))
            ->line('We appreciate your understanding.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Profile Application Update',
            'message' => 'Your lawyer profile application was not approved. Please check your dashboard for more information.',
            'type' => 'warning',
            'action_url' => route('lawyer.dashboard'),
            'action_text' => 'View Dashboard',
        ];
    }
}
