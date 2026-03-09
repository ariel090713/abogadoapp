<?php

namespace App\Livewire\Resources;

use App\Models\Event;
use Livewire\Component;

class EventView extends Component
{
    public $slug;
    public $event;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->event = Event::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $this->event->incrementViews();
    }

    public function render()
    {
        $upcomingEvents = Event::where('is_published', true)
            ->where('event_date', '>=', now())
            ->where('id', '!=', $this->event->id)
            ->orderBy('event_date')
            ->take(3)
            ->get();

        return view('livewire.resources.event-view', [
            'upcomingEvents' => $upcomingEvents,
        ])->layout('layouts.guest');
    }
}
