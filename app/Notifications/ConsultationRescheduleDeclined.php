<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationRescheduleDeclined extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $decliner = $isClient ? $this->consultation->lawyer : $this->consultation->client;
        
        return (new MailMessage)
            ->subject('Reschedule Request Declined')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line($decliner->full_name . ' has declined your reschedule request.')
            ->line('Original Schedule: ' . $this->consultation->scheduled_at->format('F d, Y g:i A'))
            ->line('Reason: ' . ($this->consultation->reschedule_decline_reason ?? 'No reason provided'))
            ->action('View Consultation', route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation->id))
            ->line('The consultation will proceed as originally scheduled.');
    }

    public function toArray(object $notifiable): array
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        
        return [
            'type' => 'consultation_reschedule_declined',
            'consultation_id' => $this->consultation->id,
            'title' => 'Reschedule Request Declined',
            'message' => 'Your reschedule request has been declined',
            'action_url' => route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation->id),
            'decline_reason' => $this->consultation->reschedule_decline_reason,
        ];
    }
}
