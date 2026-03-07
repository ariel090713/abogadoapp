<?php

namespace App\Notifications;

use App\Models\DocumentDraftingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRevisionRequested extends Notification implements ShouldQueue
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
            ->subject('Document Revision Requested')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A client has requested revisions for a document.')
            ->line('Document: ' . $this->request->document_name)
            ->line('Client: ' . $this->request->client->name)
            ->line('Revisions used: ' . $this->request->revisions_used . ' of ' . $this->request->revisions_allowed)
            ->line('Revision notes: ' . $this->request->revision_notes)
            ->action('View Request', route('lawyer.document-request.details', $this->request->id))
            ->line('Please review the revision notes and update the document accordingly.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'document_revision_requested',
            'title' => 'Revision Requested',
            'message' => $this->request->client->name . ' requested revisions for ' . $this->request->document_name,
            'request_id' => $this->request->id,
            'client_name' => $this->request->client->name,
            'document_name' => $this->request->document_name,
            'revisions_used' => $this->request->revisions_used,
            'revisions_allowed' => $this->request->revisions_allowed,
            'url' => route('lawyer.document-request.details', $this->request->id),
            'action_url' => route('lawyer.document-request.details', $this->request->id),
        ];
    }
}
