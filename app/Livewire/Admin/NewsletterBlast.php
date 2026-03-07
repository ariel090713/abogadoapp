<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class NewsletterBlast extends Component
{
    public $subject = '';
    public $message = '';
    public $showPreview = false;

    protected $rules = [
        'subject' => 'required|min:3|max:255',
        'message' => 'required|min:10',
    ];

    protected $listeners = ['contentUpdated'];

    public function contentUpdated($content)
    {
        $this->message = $content;
    }

    public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
    }

    public function sendNewsletter()
    {
        $this->validate();

        try {
            $subscribers = NewsletterSubscriber::subscribed()->get();

            if ($subscribers->isEmpty()) {
                session()->flash('error', 'No active subscribers found.');
                return;
            }

            // Send email to all subscribers using custom template
            foreach ($subscribers as $subscriber) {
                Mail::send('emails.newsletter', [
                    'subject' => $this->subject,
                    'messageContent' => $this->message,
                    'unsubscribeUrl' => route('newsletter.unsubscribe', $subscriber->token),
                ], function ($message) use ($subscriber) {
                    $message->to($subscriber->email)
                            ->subject($this->subject);
                });
            }

            session()->flash('success', "Newsletter sent successfully to {$subscribers->count()} subscribers!");
            
            $this->reset(['subject', 'message', 'showPreview']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send newsletter. Please try again.');
        }
    }

    public function render()
    {
        $subscriberCount = NewsletterSubscriber::subscribed()->count();
        $totalSubscribers = NewsletterSubscriber::count();
        $unsubscribedCount = NewsletterSubscriber::where('is_subscribed', false)->count();

        return view('livewire.admin.newsletter-blast', [
            'subscriberCount' => $subscriberCount,
            'totalSubscribers' => $totalSubscribers,
            'unsubscribedCount' => $unsubscribedCount,
        ])->layout('layouts.dashboard', ['title' => 'Newsletter Blast']);
    }
}
