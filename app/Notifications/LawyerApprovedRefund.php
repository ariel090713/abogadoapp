<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LawyerApprovedRefund extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
            ->subject('Refund Request - Lawyer Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Good news! The lawyer has approved your refund request.')
            ->line('**Transaction Reference:** ' . $this->refund->transaction->reference_number)
            ->line('**Refund Amount:** ₱' . number_format($this->refund->refund_amount, 2));

        if ($this->refund->lawyer_notes) {
            $mail->line('**Lawyer\'s Note:** ' . $this->refund->lawyer_notes);
        }

        return $mail
            ->line('Your refund is now being processed by our admin team. You will receive another notification once it is completed.')
            ->action('View Transaction', route('client.transactions.details', $this->refund->transaction_id))
            ->line('Thank you for your patience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'refund_lawyer_approved',
            'refund_id' => $this->refund->id,
            'transaction_id' => $this->refund->transaction_id,
            'amount' => $this->refund->refund_amount,
            'message' => 'Lawyer approved your refund request of ₱' . number_format($this->refund->refund_amount, 2) . '. Admin review in progress.',
            'action_url' => route('client.transactions.details', $this->refund->transaction_id),
        ];
    }
}
