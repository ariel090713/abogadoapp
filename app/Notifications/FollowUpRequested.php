<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUpRequested extends Notification implements ShouldQueue
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
            ->subject('Follow-Up Session Requested')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->request->requester->name . ' has requested a follow-up ' . $serviceTypeLabel . ' session.')
            ->line('**Reason:** ' . $this->request->description);

        if ($this->request->proposed_price) {
            $mail->line('**Proposed Price:** ₱' . number_format($this->request->proposed_price, 2));
        } else {
            $mail->line('**Price:** Free consultation');
        }

        if ($this->request->proposed_date) {
            $mail->line('**Proposed Date:** ' . $this->request->proposed_date->format('F d, Y g:i A'));
        }

        $mail->action('View Request', route('consultations.show', $this->request->consultation_id))
            ->line('Please review and respond to this request.');

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Follow-Up Session Requested',
            'message' => $this->request->requester->name . ' has requested a follow-up ' . $this->request->service_type . ' session.',
            'action_url' => route('consultations.show', $this->request->consultation_id),
            'request_id' => $this->request->id,
            'service_type' => $this->request->service_type,
            'proposed_price' => $this->request->proposed_price,
        ];
    }
}
