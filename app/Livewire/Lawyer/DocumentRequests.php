<?php

namespace App\Livewire\Lawyer;

use App\Models\DocumentDraftingRequest;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentRequests extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, paid, in_progress, revision_requested, completed
    public $categoryFilter = null; // Filter by category
    public $search = ''; // Search query
    public $showDropdown = null; // Track which dropdown is open

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleDropdown($requestId)
    {
        $this->showDropdown = $this->showDropdown === $requestId ? null : $requestId;
    }

    public function viewDetails($requestId)
    {
        return redirect()->route('lawyer.document-request.details', $requestId);
    }

    public function markAsUrgent($requestId)
    {
        // TODO: Implement mark as urgent functionality
        session()->flash('success', 'Request marked as urgent');
        $this->showDropdown = null;
    }

    public function sendMessage($requestId)
    {
        // TODO: Implement send message functionality
        session()->flash('info', 'Messaging feature coming soon');
        $this->showDropdown = null;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryFilter = null;
        $this->filter = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $query = DocumentDraftingRequest::with(['client', 'service'])
            ->where('lawyer_id', auth()->id());

        // Search by document name or client name
        if ($this->search) {
            $query->where(function($q) {
                $q->where('document_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function($clientQuery) {
                      $clientQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter by status
        if ($this->filter === 'pending_payment') {
            $query->where('status', 'pending_payment');
        } elseif ($this->filter === 'paid') {
            $query->where('status', 'paid');
        } elseif ($this->filter === 'in_progress') {
            $query->where('status', 'in_progress');
        } elseif ($this->filter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        // Filter by category (using slug)
        if ($this->categoryFilter) {
            $query->whereHas('service', function($q) {
                $q->where('category', $this->categoryFilter);
            });
        }

        $requests = $query->latest()->paginate(10);

        // Get counts for filters
        $counts = [
            'all' => DocumentDraftingRequest::where('lawyer_id', auth()->id())->count(),
            'pending_payment' => DocumentDraftingRequest::where('lawyer_id', auth()->id())->where('status', 'pending_payment')->count(),
            'paid' => DocumentDraftingRequest::where('lawyer_id', auth()->id())->where('status', 'paid')->count(),
            'in_progress' => DocumentDraftingRequest::where('lawyer_id', auth()->id())->where('status', 'in_progress')->count(),
            'revision_requested' => DocumentDraftingRequest::where('lawyer_id', auth()->id())->where('status', 'revision_requested')->count(),
            'completed' => DocumentDraftingRequest::where('lawyer_id', auth()->id())->where('status', 'completed')->count(),
        ];

        // Get categories for filter
        $categories = \App\Models\DocumentCategory::active()->ordered()->get();

        return view('livewire.lawyer.document-requests', [
            'requests' => $requests,
            'counts' => $counts,
            'categories' => $categories,
        ])->layout('layouts.dashboard', ['title' => 'Document Requests']);
    }
}
