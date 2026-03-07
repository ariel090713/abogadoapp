<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationDeclined extends Notification implements ShouldQueue
{
    use Queueable;

    public $consultation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Consultation Request Declined')
            ->line('Unfortunately, your consultation request has been declined.')
            ->line('Lawyer: ' . $this->consultation->lawyer->name);

        if ($this->consultation->decline_reason) {
            $mail->line('Reason: ' . $this->consultation->decline_reason);
        }

        return $mail
            ->action('Find Another Lawyer', url('/lawyers'))
            ->line('You can browse other available lawyers and book a new consultation.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Determine the correct route based on user role
        $actionUrl = $notifiable->isClient() 
            ? route('client.consultation.details', $this->consultation->id)
            : route('lawyer.consultation.details', $this->consultation->id);

        return [
            'consultation_id' => $this->consultation->id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'decline_reason' => $this->consultation->decline_reason,
            'message' => $this->consultation->lawyer->name . ' declined your consultation request.',
            'action_url' => $actionUrl,
            'action_text' => 'View Details',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'consultation_id' => $this->consultation->id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'message' => $this->consultation->lawyer->name . ' declined your consultation request.',
            'action_url' => '/lawyers',
        ]);
    }
}
