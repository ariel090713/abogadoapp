<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $otherParty = $isClient ? $this->consultation->lawyer->name : $this->consultation->client->name;

        return (new MailMessage)
            ->subject('Consultation Scheduled')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your consultation has been scheduled!')
            ->line('**Consultation Details:**')
            ->line('With: ' . $otherParty)
            ->line('Type: ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('Date & Time: ' . $this->consultation->scheduled_at->format('F j, Y \a\t g:i A'))
            ->line('Duration: ' . $this->consultation->duration . ' minutes')
            ->line('')
            ->line('You will receive reminders 24 hours, 1 hour, and 15 minutes before the scheduled time.')
            ->action('View Details', route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation))
            ->line('Please be ready a few minutes before the scheduled time.');
    }

    public function toArray(object $notifiable): array
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $otherParty = $isClient ? $this->consultation->lawyer->name : $this->consultation->client->name;

        return [
            'type' => 'consultation_scheduled',
            'consultation_id' => $this->consultation->id,
            'other_party' => $otherParty,
            'scheduled_at' => $this->consultation->scheduled_at->toDateTimeString(),
            'message' => 'Consultation with ' . $otherParty . ' scheduled for ' . $this->consultation->scheduled_at->format('M d, Y g:i A'),
            'icon' => '📅',
            'action_url' => route($isClient ? 'client.consultation.details' : 'lawyer.consultation.details', $this->consultation),
        ];
    }
}
