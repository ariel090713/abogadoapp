<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundRejected extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
            ->subject('Refund Request Update - AbogadoMo')
            ->greeting('Hello!')
            ->line('We have reviewed your refund request.')
            ->line('Transaction: ' . $this->refund->transaction->reference_number)
            ->line('Requested Amount: ₱' . number_format($this->refund->refund_amount, 2))
            ->line('Unfortunately, we are unable to approve your refund request at this time.');

        if ($this->refund->rejection_reason) {
            $mail->line('Reason: ' . $this->refund->rejection_reason);
        }

        $mail->line('If you have any questions or concerns, please contact our support team.')
            ->action('View Transaction', route('client.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for your understanding.');

        return $mail;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'refund_rejected',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'rejection_reason' => $this->refund->rejection_reason,
            'message' => 'Your refund request of ₱' . number_format($this->refund->refund_amount, 2) . ' has been rejected.',
            'action_url' => route('client.transactions.details', $this->refund->transaction_id),
        ];
    }
}
