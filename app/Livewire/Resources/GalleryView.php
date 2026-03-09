<?php

namespace App\Livewire\Resources;

use App\Models\Gallery;
use Livewire\Component;

class GalleryView extends Component
{
    public $slug;
    public $gallery;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->gallery = Gallery::where('slug', $slug)
            ->where('is_published', true)
            ->with('items')
            ->firstOrFail();
        
        $this->gallery->incrementViews();
    }

    public function render()
    {
        $relatedGalleries = Gallery::where('is_published', true)
            ->where('type', $this->gallery->type)
            ->where('id', '!=', $this->gallery->id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.resources.gallery-view', [
            'relatedGalleries' => $relatedGalleries,
        ])->layout('layouts.guest');
    }
}
