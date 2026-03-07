<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundRejectedForLawyer extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Refund $refund
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Refund Request Rejected by Admin')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The admin has rejected a refund request for one of your transactions.')
            ->line('**Transaction Reference:** ' . $this->refund->transaction->reference_number)
            ->line('**Refund Amount:** ₱' . number_format($this->refund->refund_amount, 2))
            ->line('**Reason:** ' . $this->refund->getReasonLabel())
            ->line('The client has been notified of this decision.')
            ->action('View Transaction', route('lawyer.transactions.details', $this->refund->transaction_id))
            ->line('Thank you.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'refund_rejected_lawyer',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Admin rejected refund request of ₱' . number_format($this->refund->refund_amount, 2) . ' for transaction ' . $this->refund->transaction->reference_number,
            'action_url' => route('lawyer.transactions.details', $this->refund->transaction_id),
        ];
    }
}
