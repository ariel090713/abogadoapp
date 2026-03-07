<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteAccepted extends Notification implements ShouldQueue
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
            ->subject('Client Accepted Your Quote')
            ->greeting('Hello Atty. ' . $notifiable->name . '!')
            ->line($this->consultation->client->name . ' has accepted your quote for the consultation request.')
            ->line('**Service:** ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('**Quoted Amount:** ₱' . number_format($this->consultation->quoted_price, 2))
            ->line('**Your Earnings:** ₱' . number_format($this->consultation->quoted_price, 2))
            ->line('')
            ->line('The client now has 1 hour to complete the payment. Once paid, the consultation will be confirmed and you will be notified.')
            ->action('View Consultation', route('lawyer.consultations'))
            ->line('Thank you for using AbogadoMo App!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'consultation_id' => $this->consultation->id,
            'client_name' => $this->consultation->client->name,
            'quoted_price' => $this->consultation->quoted_price,
            'message' => $this->consultation->client->name . ' accepted your quote of ₱' . number_format($this->consultation->quoted_price, 2) . '. Waiting for payment.',
            'action_url' => route('lawyer.consultations'),
        ];
    }
}
