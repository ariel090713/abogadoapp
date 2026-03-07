<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
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
        $platformFee = $this->consultation->platform_fee;
        $lawyerEarnings = $this->consultation->quoted_price;
        
        return (new MailMessage)
            ->subject('💰 Payment Received - New Consultation Confirmed')
            ->greeting('Hello Atty. ' . $notifiable->name . '!')
            ->line('Great news! ' . $this->consultation->client->name . ' has completed payment for the consultation. 🎉')
            ->line('---')
            ->line('**Consultation Details:**')
            ->line('**Service:** ' . ucfirst(str_replace('_', ' ', $this->consultation->consultation_type)))
            ->line('**Client:** ' . $this->consultation->client->name)
            ->line('---')
            ->line('**Payment Breakdown:**')
            ->line('**Total Amount:** ₱' . number_format($this->consultation->total_amount, 2))
            ->line('**Platform Fee (10%):** ₱' . number_format($platformFee, 2))
            ->line('**Your Earnings:** ₱' . number_format($lawyerEarnings, 2))
            ->line('---')
            ->line('Please schedule the consultation session at your earliest convenience.')
            ->action('Schedule Consultation', route('lawyer.consultation.details', $this->consultation))
            ->line('Your earnings will be processed according to our payout schedule.')
            ->salutation('Best regards,  
The AbogadoMo Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_received',
            'consultation_id' => $this->consultation->id,
            'client_name' => $this->consultation->client->name,
            'amount' => $this->consultation->quoted_price,
            'message' => 'Payment received! ' . $this->consultation->client->name . ' paid ₱' . number_format($this->consultation->total_amount, 2),
            'icon' => '💰',
            'action_url' => route('lawyer.consultation.details', $this->consultation),
        ];
    }
}
