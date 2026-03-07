<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return view('newsletter.unsubscribe', [
                'status' => 'error',
                'message' => 'Invalid unsubscribe link.'
            ]);
        }

        if (!$subscriber->is_subscribed) {
            return view('newsletter.unsubscribe', [
                'status' => 'info',
                'message' => 'You are already unsubscribed from our newsletter.'
            ]);
        }

        return view('newsletter.unsubscribe', [
            'status' => 'confirm',
            'subscriber' => $subscriber
        ]);
    }

    public function confirmUnsubscribe(Request $request, $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Invalid unsubscribe link.');
        }

        $subscriber->unsubscribe();

        return view('newsletter.unsubscribe', [
            'status' => 'success',
            'message' => 'You have been successfully unsubscribed from our newsletter.'
        ]);
    }
}
