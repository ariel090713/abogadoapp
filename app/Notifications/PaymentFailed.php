<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation,
        public ?string $errorMessage = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Payment Failed - Please Try Again')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your payment for the consultation could not be processed.')
            ->line('**Consultation Details:**')
            ->line('Service: ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('Lawyer: ' . $this->consultation->lawyer->name)
            ->line('Amount: ₱' . number_format($this->consultation->total_amount, 2));

        if ($this->errorMessage) {
            $mail->line('**Error:** ' . $this->errorMessage);
        }

        return $mail
            ->line('')
            ->line('Please try again or use a different payment method.')
            ->action('Retry Payment', route('client.consultation.details', $this->consultation))
            ->line('If the problem persists, please contact support.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_failed',
            'consultation_id' => $this->consultation->id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'amount' => $this->consultation->total_amount,
            'error_message' => $this->errorMessage,
            'message' => 'Payment failed. Please try again.',
            'icon' => '❌',
            'action_url' => route('client.consultation.details', $this->consultation),
        ];
    }
}
