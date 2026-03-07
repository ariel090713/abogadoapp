<?php

namespace App\Notifications;

use App\Models\DocumentDraftingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRequestReceived extends Notification implements ShouldQueue
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
            ->subject('New Document Request Received')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new document drafting request.')
            ->line('Document: ' . $this->request->document_name)
            ->line('Client: ' . $this->request->client->name)
            ->line('Amount: ₱' . number_format($this->request->price, 2))
            ->action('View Request', route('lawyer.document-request.details', $this->request->id))
            ->line('Please review the request and start working on it once payment is confirmed.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'document_request_received',
            'title' => 'New Document Request',
            'message' => $this->request->client->name . ' requested ' . $this->request->document_name,
            'request_id' => $this->request->id,
            'client_name' => $this->request->client->name,
            'document_name' => $this->request->document_name,
            'amount' => $this->request->price,
            'url' => route('lawyer.document-request.details', $this->request->id),
            'action_url' => route('lawyer.document-request.details', $this->request->id),
        ];
    }
}
