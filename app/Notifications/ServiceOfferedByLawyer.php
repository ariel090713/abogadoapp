<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceOfferedByLawyer extends Notification implements ShouldQueue
{
    use Queueable;

    public $consultation;

    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $serviceType = ucfirst(str_replace('_', ' ', $this->consultation->consultation_type));
        $price = $this->consultation->quoted_price == 0 ? 'FREE' : '₱' . number_format($this->consultation->quoted_price, 2);
        
        return (new MailMessage)
            ->subject('New Service Offer from Your Lawyer')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your lawyer, ' . $this->consultation->lawyer->name . ', has offered an additional service for your case.')
            ->line('**Service:** ' . $serviceType)
            ->line('**Price:** ' . $price)
            ->line('**Title:** ' . $this->consultation->title)
            ->line('**Notes:** ' . $this->consultation->quote_notes)
            ->action('View & Respond', route('client.consultation-thread.details', $this->consultation->parent_consultation_id))
            ->line('Please review the offer and respond at your earliest convenience.');
    }

    public function toArray($notifiable)
    {
        return [
            'consultation_id' => $this->consultation->id,
            'parent_case_id' => $this->consultation->parent_consultation_id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'service_type' => $this->consultation->consultation_type,
            'price' => $this->consultation->quoted_price,
            'title' => $this->consultation->title,
            'message' => $this->consultation->lawyer->name . ' has offered a new service for your case.',
            'action_url' => route('client.consultation-thread.details', $this->consultation->parent_consultation_id),
        ];
    }
}
