<?php

namespace App\Livewire\Resources;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class BlogsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Blog::where('is_published', true);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        $blogs = $query->latest()->paginate(12);

        return view('livewire.resources.blogs-index', [
            'blogs' => $blogs,
        ])->layout('layouts.guest');
    }
}
