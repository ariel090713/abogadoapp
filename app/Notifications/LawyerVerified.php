<?php

namespace App\Notifications;

use App\Models\LawyerProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LawyerVerified extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public LawyerProfile $lawyerProfile
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Lawyer Profile Has Been Verified!')
            ->greeting('Congratulations, ' . $notifiable->name . '!')
            ->line('Your lawyer profile has been successfully verified by our admin team.')
            ->line('You can now start accepting consultations and offering your legal services on AbogadoMo.')
            ->action('View Your Profile', route('lawyer.profile'))
            ->line('Thank you for joining our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Profile Verified',
            'message' => 'Your lawyer profile has been verified! You can now start accepting consultations.',
            'type' => 'success',
            'action_url' => route('lawyer.profile'),
            'action_text' => 'View Profile',
        ];
    }
}
