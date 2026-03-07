<?php

namespace App\Livewire;

use App\Models\LawyerDocumentService;
use Livewire\Component;
use Livewire\WithPagination;

class BrowseDocuments extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'random'; // random by default for fairness

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function searchByCategory($category)
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function render()
    {
        // Only show results if there's a search query or category selected
        $documents = collect();
        $hasSearch = !empty($this->search) || !empty($this->category);

        if ($hasSearch) {
            $query = LawyerDocumentService::with(['lawyer.lawyerProfile', 'template'])
                ->where('is_active', true)
                ->whereHas('lawyer', function($q) {
                    $q->where('role', 'lawyer')
                      ->whereHas('lawyerProfile', function($q2) {
                          $q2->where('is_verified', true);
                      });
                });

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->category) {
                $query->where('category', $this->category);
            }

            // Sorting
            switch ($this->sortBy) {
                case 'popular':
                    $query->orderBy('total_orders', 'desc');
                    break;
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'random':
                default:
                    $query->inRandomOrder();
                    break;
            }

            $documents = $query->paginate(12);
        }

        // Get categories from database - ensure "other" is always last
        $categories = \App\Models\DocumentCategory::active()
            ->ordered()
            ->get();
        
        // Separate "other" category
        $otherCategory = $categories->where('slug', 'other')->first();
        $regularCategories = $categories->where('slug', '!=', 'other');
        
        // Combine with "other" at the end
        $allCategories = $otherCategory 
            ? $regularCategories->push($otherCategory) 
            : $regularCategories;

        return view('livewire.browse-documents', [
            'documents' => $documents,
            'categories' => $allCategories,
            'hasSearch' => $hasSearch,
        ])->layout('layouts.guest', ['title' => 'Browse Documents']);
    }
}
