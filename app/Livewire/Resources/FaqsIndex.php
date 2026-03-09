<?php

namespace App\Livewire\Resources;

use App\Models\Faq;
use Livewire\Component;

class FaqsIndex extends Component
{
    public $selectedCategory = 'all';
    public $searchQuery = '';
    public $openFaqId = null;

    public function toggleFaq($faqId)
    {
        $this->openFaqId = $this->openFaqId === $faqId ? null : $faqId;
    }

    public function render()
    {
        $faqs = Faq::published()->ordered();

        if ($this->selectedCategory !== 'all') {
            $faqs = $faqs->where('category', $this->selectedCategory);
        }

        if ($this->searchQuery) {
            $faqs = $faqs->where(function ($query) {
                $query->where('question', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('answer', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $categories = Faq::published()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('livewire.resources.faqs-index', [
            'faqs' => $faqs->get(),
            'categories' => $categories,
        ])->layout('layouts.guest');
    }
}
