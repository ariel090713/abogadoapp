<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationQuoted extends Notification implements ShouldQueue
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
            ->subject('Quote Received for Your Consultation Request')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Good news! ' . $this->consultation->lawyer->name . ' has provided a quote for your consultation request.')
            ->line('**Service:** ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('**Quoted Amount:** ₱' . number_format($this->consultation->quoted_price, 2))
            ->line('**Platform Fee (10%):** ₱' . number_format($this->consultation->platform_fee, 2))
            ->line('**Total Amount:** ₱' . number_format($this->consultation->total_amount, 2))
            ->line('')
            ->line('**Quote Explanation:**')
            ->line($this->consultation->quote_notes)
            ->line('')
            ->action('View Quote & Respond', route('client.consultations'))
            ->line('You can accept or decline this quote from your consultations page.')
            ->line('Thank you for using AbogadoMo App!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'consultation_id' => $this->consultation->id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'quoted_price' => $this->consultation->quoted_price,
            'total_amount' => $this->consultation->total_amount,
            'message' => $this->consultation->lawyer->name . ' has provided a quote of ₱' . number_format($this->consultation->quoted_price, 2) . ' for your consultation request.',
            'action_url' => route('client.consultations'),
        ];
    }
}
