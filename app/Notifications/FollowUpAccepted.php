<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUpAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ServiceRequest $request,
        public Consultation $newConsultation
    ) {
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
            ->subject('Follow-Up Request Accepted')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your follow-up request for a ' . $serviceTypeLabel . ' has been accepted.')
            ->line('A new consultation session has been created.');

        if ($this->newConsultation->quoted_price) {
            $mail->line('**Price:** ₱' . number_format($this->newConsultation->quoted_price, 2))
                ->line('**Payment Deadline:** ' . $this->newConsultation->payment_deadline->format('F d, Y g:i A'))
                ->line('Please complete the payment to proceed with the consultation.');
        } else {
            $mail->line('**Price:** Free consultation');
        }

        if ($this->newConsultation->scheduled_at) {
            $mail->line('**Scheduled:** ' . $this->newConsultation->scheduled_at->format('F d, Y g:i A'));
        }

        $mail->action('View Consultation', route('consultations.show', $this->newConsultation->id));

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Follow-Up Request Accepted',
            'message' => 'Your follow-up request has been accepted. A new session has been created.',
            'action_url' => route('consultations.show', $this->newConsultation->id),
            'consultation_id' => $this->newConsultation->id,
        ];
    }
}
