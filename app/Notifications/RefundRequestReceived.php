<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundRequestReceived extends Notification implements ShouldQueue
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
            ->subject('Refund Request Received - AbogadoMo')
            ->greeting('Hello!')
            ->line('We have received your refund request.')
            ->line('Transaction: ' . $this->refund->transaction->reference_number)
            ->line('Requested Amount: ₱' . number_format($this->refund->refund_amount, 2))
            ->line('Reason: ' . $this->refund->getReasonLabel())
            ->line('Our team will review your request and get back to you within 1-2 business days.')
            ->action('View Transaction', route('client.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for your patience!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'refund_request_received',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Your refund request of ₱' . number_format($this->refund->refund_amount, 2) . ' has been received and is under review.',
            'action_url' => route('client.transactions.details', $this->refund->transaction_id),
        ];
    }
}
