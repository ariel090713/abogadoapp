<?php

namespace App\Notifications;

use App\Models\DocumentDraftingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $tries = 3;
    public $backoff = [60, 300, 900]; // Retry after 1 min, 5 min, 15 min

    public function __construct(DocumentDraftingRequest $request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Document is Ready!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your document has been completed.')
            ->line('Document: ' . $this->request->document_name)
            ->line('Lawyer: ' . $this->request->lawyer->name)
            ->action('Download Document', route('document.details', $this->request->id))
            ->line('You can now download your completed document.')
            ->line('If you need any revisions, you can request them from the document details page.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'document_completed',
            'title' => 'Document Ready',
            'message' => 'Your ' . $this->request->document_name . ' has been completed',
            'request_id' => $this->request->id,
            'lawyer_name' => $this->request->lawyer->name,
            'document_name' => $this->request->document_name,
            'url' => route('document.details', $this->request->id),
            'action_url' => route('document.details', $this->request->id),
        ];
    }
}
