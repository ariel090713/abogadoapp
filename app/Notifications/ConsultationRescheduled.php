<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationRescheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation,
        public bool $autoAccepted = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $message = $this->autoAccepted 
            ? 'Your consultation has been automatically rescheduled.'
            : 'Your reschedule request has been approved.';
        
        return (new MailMessage)
            ->subject('Consultation Rescheduled')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line($message)
            ->line('New Schedule: ' . $this->consultation->scheduled_at->format('F d, Y g:i A'))
            ->action('View Consultation', route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation->id))
            ->line('Please make sure you are available at the new schedule.');
    }

    public function toArray(object $notifiable): array
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        
        return [
            'type' => 'consultation_rescheduled',
            'consultation_id' => $this->consultation->id,
            'title' => 'Consultation Rescheduled',
            'message' => 'Your consultation has been rescheduled to ' . $this->consultation->scheduled_at->format('M d, Y g:i A'),
            'action_url' => route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation->id),
            'new_schedule' => $this->consultation->scheduled_at,
            'auto_accepted' => $this->autoAccepted,
        ];
    }
}
