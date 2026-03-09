<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use Livewire\Component;
use Livewire\WithPagination;

class FaqManagement extends Component
{
    use WithPagination;

    public $question = '';
    public $answer = '';
    public $category = 'general';
    public $order = 0;
    public $is_published = true;
    public $editingId = null;
    public $searchQuery = '';
    public $filterCategory = 'all';

    protected $rules = [
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
        'category' => 'required|string|max:50',
        'order' => 'required|integer|min:0',
        'is_published' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $faq = Faq::findOrFail($this->editingId);
            $faq->update([
                'question' => $this->question,
                'answer' => $this->answer,
                'category' => $this->category,
                'order' => $this->order,
                'is_published' => $this->is_published,
            ]);
            session()->flash('success', 'FAQ updated successfully');
        } else {
            Faq::create([
                'question' => $this->question,
                'answer' => $this->answer,
                'category' => $this->category,
                'order' => $this->order,
                'is_published' => $this->is_published,
            ]);
            session()->flash('success', 'FAQ created successfully');
        }

        $this->reset(['question', 'answer', 'category', 'order', 'is_published', 'editingId']);
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $this->editingId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->category = $faq->category;
        $this->order = $faq->order;
        $this->is_published = $faq->is_published;
    }

    public function cancelEdit()
    {
        $this->reset(['question', 'answer', 'category', 'order', 'is_published', 'editingId']);
    }

    public function delete($id)
    {
        Faq::findOrFail($id)->delete();
        session()->flash('success', 'FAQ deleted successfully');
    }

    public function togglePublish($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['is_published' => !$faq->is_published]);
        session()->flash('success', 'FAQ status updated');
    }

    public function render()
    {
        $faqs = Faq::query()
            ->when($this->searchQuery, function ($query) {
                $query->where('question', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('answer', 'like', '%' . $this->searchQuery . '%');
            })
            ->when($this->filterCategory !== 'all', function ($query) {
                $query->where('category', $this->filterCategory);
            })
            ->ordered()
            ->paginate(10);

        $categories = Faq::select('category')->distinct()->pluck('category');

        return view('livewire.admin.faq-management', [
            'faqs' => $faqs,
            'categories' => $categories,
        ])->layout('layouts.dashboard');
    }
}
