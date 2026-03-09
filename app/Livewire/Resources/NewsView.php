<?php

namespace App\Livewire\Resources;

use App\Models\News;
use Livewire\Component;

class NewsView extends Component
{
    public $slug;
    public $news;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->news = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $this->news->incrementViews();
    }

    public function render()
    {
        $relatedNews = News::where('is_published', true)
            ->where('id', '!=', $this->news->id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.resources.news-view', [
            'relatedNews' => $relatedNews,
        ])->layout('layouts.guest');
    }
}
