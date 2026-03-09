<?php

namespace App\Livewire\Resources;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = News::where('is_published', true);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        $news = $query->latest()->paginate(12);

        return view('livewire.resources.news-index', [
            'news' => $news,
        ])->layout('layouts.guest');
    }
}
