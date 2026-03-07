<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];
        
        // Only broadcast if user was active in last 5 minutes (cost optimization)
        if ($notifiable->isOnline()) {
            $channels[] = 'broadcast';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Payment Successful - Consultation Confirmed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment has been successfully processed! 🎉')
            ->line('---')
            ->line('**Consultation Details:**')
            ->line('**Service:** ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('**Lawyer:** ' . $this->consultation->lawyer->name)
            ->line('**Amount Paid:** ₱' . number_format($this->consultation->total_amount, 2))
            ->line('---')
            ->line('Your consultation is now confirmed. The lawyer will schedule your session shortly.')
            ->action('View Consultation Details', route('client.consultation.details', $this->consultation))
            ->line('If you have any questions, feel free to reach out to us.')
            ->salutation('Best regards,  
The AbogadoMo Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_successful',
            'consultation_id' => $this->consultation->id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'amount' => $this->consultation->total_amount,
            'message' => 'Payment successful! Your consultation with ' . $this->consultation->lawyer->name . ' is confirmed.',
            'icon' => '💰',
            'action_url' => route('client.consultation.details', $this->consultation),
        ];
    }
}
