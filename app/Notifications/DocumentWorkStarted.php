<?php

namespace App\Notifications;

use App\Models\DocumentDraftingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentWorkStarted extends Notification implements ShouldQueue
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
        $completionDate = $this->request->completion_deadline 
            ? $this->request->completion_deadline->format('M d, Y') 
            : 'soon';

        return (new MailMessage)
            ->subject('Work Started on Your Document')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Good news! Your lawyer has started working on your document.')
            ->line('Document: ' . $this->request->document_name)
            ->line('Lawyer: ' . $this->request->lawyer->name)
            ->line('Expected completion: ' . $completionDate)
            ->action('View Request', route('document.details', $this->request->id))
            ->line('You will be notified once the document is ready.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'document_work_started',
            'title' => 'Work Started',
            'message' => $this->request->lawyer->name . ' started working on ' . $this->request->document_name,
            'request_id' => $this->request->id,
            'lawyer_name' => $this->request->lawyer->name,
            'document_name' => $this->request->document_name,
            'completion_deadline' => $this->request->completion_deadline?->toDateTimeString(),
            'url' => route('document.details', $this->request->id),
            'action_url' => route('document.details', $this->request->id),
        ];
    }
}
