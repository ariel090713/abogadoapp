<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundCompletedForLawyer extends Notification implements ShouldQueue
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
            ->subject('Refund Processed Successfully')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A refund has been successfully processed for one of your transactions.')
            ->line('**Transaction Reference:** ' . $this->refund->transaction->reference_number)
            ->line('**Refund Amount:** ₱' . number_format($this->refund->refund_amount, 2))
            ->line('The client has received their refund.')
            ->action('View Transaction', route('lawyer.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for using AbogadoMo.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'refund_completed_lawyer',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Refund of ₱' . number_format($this->refund->refund_amount, 2) . ' has been processed for transaction ' . $this->refund->transaction->reference_number,
            'action_url' => route('lawyer.transactions.details', $this->refund->transaction_id),
        ];
    }
}
