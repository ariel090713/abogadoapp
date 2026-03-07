<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use Notifiable;
    protected $fillable = [
        'email',
        'token',
        'is_subscribed',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_subscribed' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->token)) {
                $subscriber->token = Str::random(64);
            }
            if (empty($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }

    public function unsubscribe()
    {
        $this->update([
            'is_subscribed' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    public function resubscribe()
    {
        $this->update([
            'is_subscribed' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ]);
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
