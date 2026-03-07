<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation,
        public string $timeframe
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $otherParty = $isClient ? $this->consultation->lawyer->name : $this->consultation->client->name;

        return (new MailMessage)
            ->subject("Consultation Reminder - {$this->timeframe}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("This is a reminder that you have a consultation scheduled in {$this->timeframe}.")
            ->line("**Consultation Details:**")
            ->line("With: {$otherParty}")
            ->line("Type: " . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line("Date & Time: " . $this->consultation->scheduled_at->format('F j, Y \a\t g:i A'))
            ->line("Duration: {$this->consultation->duration} minutes")
            ->action('View Consultation', route($isClient ? 'client.consultations' : 'lawyer.consultations'))
            ->line('Please be ready a few minutes before the scheduled time.');
    }

    public function toArray($notifiable): array
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $otherParty = $isClient ? $this->consultation->lawyer->name : $this->consultation->client->name;

        return [
            'consultation_id' => $this->consultation->id,
            'type' => 'consultation_reminder',
            'timeframe' => $this->timeframe,
            'message' => "Consultation with {$otherParty} in {$this->timeframe}",
            'scheduled_at' => $this->consultation->scheduled_at->toDateTimeString(),
        ];
    }
}
