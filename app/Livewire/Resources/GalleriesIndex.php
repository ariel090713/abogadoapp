<?php

namespace App\Livewire\Resources;

use App\Models\Gallery;
use Livewire\Component;
use Livewire\WithPagination;

class GalleriesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $type = 'all'; // all, photo, video

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Gallery::where('is_published', true)->with('items');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type !== 'all') {
            $query->where('type', $this->type);
        }

        $galleries = $query->latest()->paginate(12);

        return view('livewire.resources.galleries-index', [
            'galleries' => $galleries,
        ])->layout('layouts.guest');
    }
}
