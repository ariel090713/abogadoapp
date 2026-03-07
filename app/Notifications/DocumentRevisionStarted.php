<?php

namespace App\Notifications;

use App\Models\DocumentDraftingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRevisionStarted extends Notification implements ShouldQueue
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
            ->subject('Revision Work Started')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your lawyer has started working on the requested revisions.')
            ->line('Document: ' . $this->request->document_name)
            ->line('Lawyer: ' . $this->request->lawyer->name)
            ->line('Revisions: ' . $this->request->revisions_used . ' of ' . $this->request->revisions_allowed)
            ->action('View Request', route('document.details', $this->request->id))
            ->line('You will be notified once the revised document is ready.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'document_revision_started',
            'title' => 'Revision Started',
            'message' => $this->request->lawyer->name . ' started revising ' . $this->request->document_name,
            'request_id' => $this->request->id,
            'lawyer_name' => $this->request->lawyer->name,
            'document_name' => $this->request->document_name,
            'revisions_used' => $this->request->revisions_used,
            'revisions_allowed' => $this->request->revisions_allowed,
            'url' => route('document.details', $this->request->id),
            'action_url' => route('document.details', $this->request->id),
        ];
    }
}
