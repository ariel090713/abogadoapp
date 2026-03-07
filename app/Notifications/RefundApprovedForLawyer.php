<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundApprovedForLawyer extends Notification implements ShouldQueue
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
            ->subject('Refund Request Approved by Admin')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The admin has approved a refund request for one of your transactions.')
            ->line('**Transaction Reference:** ' . $this->refund->transaction->reference_number)
            ->line('**Refund Amount:** ₱' . number_format($this->refund->refund_amount, 2))
            ->line('**Reason:** ' . $this->refund->getReasonLabel())
            ->line('The refund will be processed and the client will receive their money back.')
            ->action('View Transaction', route('lawyer.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for your cooperation.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'refund_approved_lawyer',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Admin approved refund of ₱' . number_format($this->refund->refund_amount, 2) . ' for transaction ' . $this->refund->transaction->reference_number,
            'action_url' => route('lawyer.transactions.details', $this->refund->transaction_id),
        ];
    }
}
