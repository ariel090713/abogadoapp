<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationCancelled extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Consultation Cancelled - Payment Deadline Expired')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your consultation has been cancelled because payment was not completed within the deadline.')
            ->line('Consultation: ' . $this->consultation->title)
            ->line('Lawyer: ' . $this->consultation->lawyer->name)
            ->line('Reason: ' . $this->consultation->cancel_reason)
            ->line('You can book a new consultation anytime.')
            ->action('Find Lawyers', url('/lawyers'))
            ->line('Thank you for using AbogadoMo App!');
    }

    public function toArray(object $notifiable): array
    {
        // Determine the correct route based on user role
        $actionUrl = $notifiable->role === 'client' 
            ? route('client.consultations')
            : route('lawyer.consultations');

        return [
            'consultation_id' => $this->consultation->id,
            'title' => $this->consultation->title,
            'lawyer_name' => $this->consultation->lawyer->name,
            'cancel_reason' => $this->consultation->cancel_reason,
            'type' => 'consultation_cancelled',
            'message' => 'Consultation "' . $this->consultation->title . '" was cancelled: ' . $this->consultation->cancel_reason,
            'action_url' => $actionUrl,
            'action_text' => 'View Consultations',
        ];
    }
}
