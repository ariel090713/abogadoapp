<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceOfferAccepted extends Notification implements ShouldQueue
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
            ->subject('Service Offer Accepted by Client')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->consultation->client->name . ' has accepted your service offer.')
            ->line('**Service:** ' . $serviceType)
            ->line('**Title:** ' . $this->consultation->title)
            ->action('View Thread Details', route('lawyer.consultation-thread.details', $this->consultation->parent_consultation_id))
            ->line('You can now proceed with the service.');
    }

    public function toArray($notifiable)
    {
        return [
            'consultation_id' => $this->consultation->id,
            'parent_case_id' => $this->consultation->parent_consultation_id,
            'client_name' => $this->consultation->client->name,
            'service_type' => $this->consultation->consultation_type,
            'title' => $this->consultation->title,
            'message' => $this->consultation->client->name . ' accepted your service offer.',
            'action_url' => route('lawyer.consultation-thread.details', $this->consultation->parent_consultation_id),
        ];
    }
}
