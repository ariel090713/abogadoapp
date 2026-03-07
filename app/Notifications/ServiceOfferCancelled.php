<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceOfferCancelled extends Notification implements ShouldQueue
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
        
        return (new MailMessage)
            ->subject('Service Offer Cancelled by Lawyer')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->consultation->lawyer->name . ' has cancelled their service offer.')
            ->line('**Service:** ' . $serviceType)
            ->line('**Title:** ' . $this->consultation->title)
            ->action('View Thread Details', route('client.consultation-thread.details', $this->consultation->parent_consultation_id))
            ->line('The offer is no longer available. You may contact your lawyer for more information.');
    }

    public function toArray($notifiable)
    {
        return [
            'consultation_id' => $this->consultation->id,
            'parent_case_id' => $this->consultation->parent_consultation_id,
            'lawyer_name' => $this->consultation->lawyer->name,
            'service_type' => $this->consultation->consultation_type,
            'title' => $this->consultation->title,
            'message' => $this->consultation->lawyer->name . ' cancelled their service offer.',
            'action_url' => route('client.consultation-thread.details', $this->consultation->parent_consultation_id),
        ];
    }
}
