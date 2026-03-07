<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email|max:255',
    ];

    public function subscribe()
    {
        $this->validate();

        try {
            $subscriber = NewsletterSubscriber::where('email', $this->email)->first();

            if ($subscriber) {
                if ($subscriber->is_subscribed) {
                    session()->flash('info', 'You are already subscribed to our newsletter!');
                } else {
                    $subscriber->resubscribe();
                    session()->flash('success', 'Welcome back! You have been resubscribed to our newsletter.');
                }
            } else {
                NewsletterSubscriber::create([
                    'email' => $this->email,
                ]);
                session()->flash('success', 'Thank you for subscribing! Check your email for confirmation.');
            }

            $this->reset('email');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
