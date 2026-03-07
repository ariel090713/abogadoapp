<?php

namespace App\Notifications;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payout $payout
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payout Completed - ₱' . number_format($this->payout->amount, 2))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your payout has been successfully processed.')
            ->line('**Amount:** ₱' . number_format($this->payout->amount, 2))
            ->line('**Method:** ' . ucfirst(str_replace('_', ' ', $this->payout->method)))
            ->line('**Reference Number:** ' . $this->payout->reference_number)
            ->line('**Date:** ' . $this->payout->processed_at->format('F d, Y h:i A'))
            ->line('The funds should reflect in your account within 1-3 business days depending on your payment method.')
            ->action('View Payout Details', route('lawyer.transactions'))
            ->line('Thank you for using AbogadoMo!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'payout_completed',
            'title' => 'Payout Completed',
            'message' => 'Your payout of ₱' . number_format($this->payout->amount, 2) . ' has been processed.',
            'payout_id' => $this->payout->id,
            'amount' => $this->payout->amount,
            'method' => $this->payout->method,
            'reference_number' => $this->payout->reference_number,
            'action_url' => route('lawyer.transactions'),
        ];
    }
}
