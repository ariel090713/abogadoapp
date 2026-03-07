<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationRequestReceived extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('📋 New Consultation Request')
            ->greeting('Hello Atty. ' . $notifiable->name . '!')
            ->line('You have received a new consultation request from a client.')
            ->line('---')
            ->line('**Request Details:**')
            ->line('**Client:** ' . $this->consultation->client->name)
            ->line('**Service:** ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('**Your Rate:** ₱' . number_format($this->consultation->rate, 2))
            ->line('---')
            ->line('Please review the request and provide a quote if needed.')
            ->action('View Request Details', url('/lawyer/consultations'))
            ->line('Respond promptly to provide excellent service to your clients.')
            ->salutation('Best regards,  
The AbogadoMo Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'consultation_id' => $this->consultation->id,
            'client_name' => $this->consultation->client->name,
            'consultation_type' => $this->consultation->consultation_type,
            'rate' => $this->consultation->rate,
            'scheduled_at' => $this->consultation->scheduled_at,
            'message' => 'New consultation request from ' . $this->consultation->client->name,
            'icon' => '📋',
            'action_url' => '/lawyer/consultations',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'consultation_id' => $this->consultation->id,
            'client_name' => $this->consultation->client->name,
            'consultation_type' => $this->consultation->consultation_type,
            'message' => 'New consultation request from ' . $this->consultation->client->name,
            'action_url' => '/lawyer/consultations',
        ]);
    }
}
