<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Consultation $consultation
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isDocumentReview = $this->consultation->consultation_type === 'document_review';
        
        return (new MailMessage)
            ->subject($isDocumentReview ? 'Document Review Completed' : 'Consultation Completed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($isDocumentReview 
                ? 'Your document review has been completed by ' . $this->consultation->lawyer->name . '.'
                : 'Your consultation with ' . $this->consultation->lawyer->name . ' has been completed.')
            ->line('Consultation: ' . $this->consultation->title)
            ->when($this->consultation->reviewed_document_path, function ($mail) {
                return $mail->line('The lawyer has uploaded a reviewed document for you to download.');
            })
            ->when($this->consultation->completion_notes, function ($mail) {
                return $mail->line('The lawyer has provided notes about the review.');
            })
            ->action('View Details', route('client.consultation.details', $this->consultation))
            ->line('You can now review the completed work and provide feedback.');
    }

    public function toArray(object $notifiable): array
    {
        $isDocumentReview = $this->consultation->consultation_type === 'document_review';
        
        // Determine the correct route based on user role
        $actionUrl = $notifiable->isClient() 
            ? route('client.consultation.details', $this->consultation->id)
            : route('lawyer.consultation.details', $this->consultation->id);
        
        return [
            'consultation_id' => $this->consultation->id,
            'consultation_title' => $this->consultation->title,
            'lawyer_name' => $this->consultation->lawyer->name,
            'type' => $this->consultation->consultation_type,
            'has_reviewed_document' => !empty($this->consultation->reviewed_document_path),
            'has_completion_notes' => !empty($this->consultation->completion_notes),
            'message' => $isDocumentReview 
                ? $this->consultation->lawyer->name . ' completed your document review.'
                : $this->consultation->lawyer->name . ' marked your consultation as completed.',
            'action_url' => $actionUrl,
            'action_text' => 'View Details',
        ];
    }
}
