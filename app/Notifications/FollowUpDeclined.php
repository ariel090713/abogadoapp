<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUpDeclined extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ServiceRequest $request)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $serviceTypeLabel = match($this->request->service_type) {
            'chat' => 'Chat Consultation',
            'video' => 'Video Consultation',
            'document_review' => 'Document Review',
            default => 'Consultation',
        };

        $mail = (new MailMessage)
            ->subject('Follow-Up Request Declined')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your follow-up request for a ' . $serviceTypeLabel . ' has been declined.');

        if ($this->request->response_notes) {
            $mail->line('**Reason:** ' . $this->request->response_notes);
        }

        $mail->line('You can submit a new request if needed.')
            ->action('View Consultation', route('consultations.show', $this->request->consultation_id));

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Follow-Up Request Declined',
            'message' => 'Your follow-up request has been declined.',
            'action_url' => route('consultations.show', $this->request->consultation_id),
            'request_id' => $this->request->id,
        ];
    }
}
