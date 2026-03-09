<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationRescheduleRequested extends Notification implements ShouldQueue
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
        $requester = $this->consultation->rescheduleRequestedBy;
        $isClient = $notifiable->id === $this->consultation->client_id;
        
        return (new MailMessage)
            ->subject('Reschedule Request for Consultation')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line($requester->full_name . ' has requested to reschedule your consultation.')
            ->line('Original Schedule: ' . $this->consultation->scheduled_at->format('F d, Y g:i A'))
            ->line('Proposed Schedule: ' . $this->consultation->proposed_scheduled_at->format('F d, Y g:i A'))
            ->line('Reason: ' . $this->consultation->reschedule_reason)
            ->action('View Consultation', route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation->id))
            ->line('Please review and respond to this reschedule request.');
    }

    public function toArray(object $notifiable): array
    {
        $requester = $this->consultation->rescheduleRequestedBy;
        $isClient = $notifiable->id === $this->consultation->client_id;
        
        return [
            'type' => 'consultation_reschedule_requested',
            'consultation_id' => $this->consultation->id,
            'title' => 'Reschedule Request',
            'message' => $requester->full_name . ' requested to reschedule your consultation',
            'action_url' => route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation->id),
            'original_schedule' => $this->consultation->scheduled_at,
            'proposed_schedule' => $this->consultation->proposed_scheduled_at,
        ];
    }
}
