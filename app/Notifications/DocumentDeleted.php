<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentDeleted extends Notification
{
    use Queueable;

    public function __construct(
        public Consultation $consultation,
        public string $filename
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Document Deleted - ' . $this->consultation->title)
            ->line($this->consultation->client->name . ' has deleted a document.')
            ->line('Consultation: ' . $this->consultation->title)
            ->line('Deleted file: ' . $this->filename)
            ->action('View Consultation', route('lawyer.consultation.details', $this->consultation->id))
            ->line('The document has been removed from the consultation.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'consultation_id' => $this->consultation->id,
            'consultation_title' => $this->consultation->title,
            'client_name' => $this->consultation->client->name,
            'filename' => $this->filename,
            'message' => $this->consultation->client->name . ' deleted ' . $this->filename,
            'action_url' => route('lawyer.consultation.details', $this->consultation->id),
        ];
    }
}
