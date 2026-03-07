<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundRequestForLawyer extends Notification implements ShouldQueue
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
            ->subject('Refund Request Received - Action Required')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A client has requested a refund for a transaction.')
            ->line('**Transaction Reference:** ' . $this->refund->transaction->reference_number)
            ->line('**Refund Amount:** ₱' . number_format($this->refund->refund_amount, 2))
            ->line('**Reason:** ' . $this->refund->getReasonLabel())
            ->line('**Client Explanation:** ' . $this->refund->detailed_reason)
            ->line('Please review this refund request and provide your response.')
            ->action('Review Refund Request', route('lawyer.transactions.details', $this->refund->transaction_id))
            ->line('Your response will help us process this request fairly.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'refund_request',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Client requested a refund of ₱' . number_format($this->refund->refund_amount, 2) . '. Please review and respond.',
            'action_url' => route('lawyer.transactions.details', $this->refund->transaction_id),
        ];
    }
}
