<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $subject;
    public $messageContent;

    public function __construct($subject, $messageContent)
    {
        $this->subject = $subject;
        $this->messageContent = $messageContent;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.newsletter', [
                'subject' => $this->subject,
                'messageContent' => $this->messageContent,
                'unsubscribeUrl' => route('newsletter.unsubscribe', $notifiable->token),
            ]);
    }
}
