<?php

namespace App\Livewire\Resources;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'upcoming'; // upcoming, past, all

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Event::where('is_published', true);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter === 'upcoming') {
            $query->where('event_date', '>=', now());
        } elseif ($this->filter === 'past') {
            $query->where('event_date', '<', now());
        }

        $events = $query->orderBy('event_date', $this->filter === 'past' ? 'desc' : 'asc')->paginate(12);

        return view('livewire.resources.events-index', [
            'events' => $events,
        ])->layout('layouts.guest');
    }
}
