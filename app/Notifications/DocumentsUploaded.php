<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentsUploaded extends Notification
{
    use Queueable;

    public function __construct(
        public Consultation $consultation,
        public int $documentCount
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $docText = $this->documentCount === 1 ? 'document' : 'documents';
        
        return (new MailMessage)
            ->subject('📄 New Documents Uploaded - ' . $this->consultation->title)
            ->greeting('Hello Atty. ' . $notifiable->name . '!')
            ->line($this->consultation->client->name . ' has uploaded **' . $this->documentCount . ' ' . $docText . '** for your review.')
            ->line('---')
            ->line('**Consultation:** ' . $this->consultation->title)
            ->line('**Client:** ' . $this->consultation->client->name)
            ->line('---')
            ->action('View Documents', route('lawyer.consultation.details', $this->consultation->id))
            ->line('Please review the uploaded documents at your earliest convenience.')
            ->salutation('Best regards,  
The AbogadoMo Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'consultation_id' => $this->consultation->id,
            'consultation_title' => $this->consultation->title,
            'client_name' => $this->consultation->client->name,
            'document_count' => $this->documentCount,
            'message' => $this->consultation->client->name . ' uploaded ' . $this->documentCount . ' document(s)',
        ];
    }
}
