<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LawyerRejectedRefund extends Notification implements ShouldQueue
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
            ->subject('Refund Request - Lawyer Response')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The lawyer has responded to your refund request.')
            ->line('**Transaction Reference:** ' . $this->refund->transaction->reference_number)
            ->line('**Refund Amount:** ₱' . number_format($this->refund->refund_amount, 2))
            ->line('**Lawyer\'s Response:** ' . ($this->refund->lawyer_notes ?? 'The lawyer has concerns about this refund request.'))
            ->line('Our admin team will review both sides and make a final decision. You will be notified of the outcome.')
            ->action('View Transaction', route('client.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for your patience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'refund_lawyer_rejected',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Lawyer responded to your refund request. Admin will review and make final decision.',
            'action_url' => route('client.transactions.details', $this->refund->transaction_id),
        ];
    }
}
