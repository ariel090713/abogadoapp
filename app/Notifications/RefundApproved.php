<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Refund $refund
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Refund Request Approved - AbogadoMo')
            ->greeting('Good news!')
            ->line('Your refund request has been approved.')
            ->line('Transaction: ' . $this->refund->transaction->reference_number)
            ->line('Refund Amount: ₱' . number_format($this->refund->refund_amount, 2))
            ->line('Your refund will be processed within 5-10 business days and credited back to your original payment method.')
            ->action('View Transaction', route('client.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for using AbogadoMo!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'refund_approved',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Your refund request of ₱' . number_format($this->refund->refund_amount, 2) . ' has been approved.',
            'action_url' => route('client.transactions.details', $this->refund->transaction_id),
        ];
    }
}
