<?php

namespace App\Livewire\Resources;

use App\Models\LegalGuide;
use Livewire\Component;

class LegalGuideView extends Component
{
    public $slug;
    public $guide;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->guide = LegalGuide::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $this->guide->incrementViews();
    }

    public function render()
    {
        $relatedGuides = LegalGuide::where('is_published', true)
            ->where('category', $this->guide->category)
            ->where('id', '!=', $this->guide->id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.resources.legal-guide-view', [
            'relatedGuides' => $relatedGuides,
        ])->layout('layouts.guest');
    }
}
