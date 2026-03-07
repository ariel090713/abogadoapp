<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationStarting extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation
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
            ->subject('Your Consultation is Starting Soon!')
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your consultation with {$otherParty} is starting in 15 minutes!")
            ->line("**Consultation Details:**")
            ->line("Type: " . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line("Time: " . $this->consultation->scheduled_at->format('g:i A'))
            ->line("Duration: {$this->consultation->duration} minutes")
            ->action('Join Now', route('consultation.room', $this->consultation))
            ->line('Please join the consultation room a few minutes early to test your connection.');
    }

    public function toArray($notifiable): array
    {
        $isClient = $notifiable->id === $this->consultation->client_id;
        $otherParty = $isClient ? $this->consultation->lawyer->name : $this->consultation->client->name;

        return [
            'consultation_id' => $this->consultation->id,
            'type' => 'consultation_starting',
            'message' => "Your consultation with {$otherParty} is starting in 15 minutes!",
            'scheduled_at' => $this->consultation->scheduled_at->toDateTimeString(),
            'action_url' => route('consultation.room', $this->consultation),
        ];
    }
}
