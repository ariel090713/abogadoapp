<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'event_type',
        'event_date',
        'location',
        'meeting_link',
        'max_participants',
        'registered_count',
        'is_published',
        'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'event_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function isUpcoming()
    {
        return $this->event_date->isFuture();
    }

    public function isPast()
    {
        return $this->event_date->isPast();
    }

    public function isFull()
    {
        return $this->max_participants && $this->registered_count >= $this->max_participants;
    }
}
