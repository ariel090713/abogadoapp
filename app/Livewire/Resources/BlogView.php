<?php

namespace App\Livewire\Resources;

use App\Models\Blog;
use Livewire\Component;

class BlogView extends Component
{
    public $slug;
    public $blog;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $this->blog->incrementViews();
    }

    public function render()
    {
        $relatedBlogs = Blog::where('is_published', true)
            ->where('category', $this->blog->category)
            ->where('id', '!=', $this->blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.resources.blog-view', [
            'relatedBlogs' => $relatedBlogs,
        ])->layout('layouts.guest');
    }
}
