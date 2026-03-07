<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationAccepted extends Notification implements ShouldQueue
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
            ->subject('Consultation Request Accepted')
            ->line('Great news! Your consultation request has been accepted.')
            ->line('Lawyer: ' . $this->consultation->lawyer->name)
            ->line('Please complete payment within 30 minutes to confirm your booking.')
            ->action('Complete Payment', url('/consultation/' . $this->consultation->id . '/payment'))
            ->line('Your slot is reserved until ' . $this->consultation->payment_deadline->format('g:i A'));
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
            'payment_deadline' => $this->consultation->payment_deadline,
            'message' => $this->consultation->lawyer->name . ' accepted your consultation request. Please complete payment.',
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
            'message' => $this->consultation->lawyer->name . ' accepted your consultation request!',
            'action_url' => '/consultation/' . $this->consultation->id . '/payment',
        ]);
    }
}
