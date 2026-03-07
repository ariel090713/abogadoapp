<?php

namespace App\Livewire\Client;

use App\Models\DocumentDraftingRequest;
use Livewire\Component;
use Livewire\WithPagination;

class MyDocuments extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, pending_payment, paid, in_progress, completed, cancelled

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DocumentDraftingRequest::with(['lawyer.lawyerProfile', 'service'])
            ->where('client_id', auth()->id());

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $requests = $query->latest()->paginate(10);

        // Get counts for filters
        $counts = [
            'all' => DocumentDraftingRequest::where('client_id', auth()->id())->count(),
            'pending_payment' => DocumentDraftingRequest::where('client_id', auth()->id())->where('status', 'pending_payment')->count(),
            'paid' => DocumentDraftingRequest::where('client_id', auth()->id())->where('status', 'paid')->count(),
            'in_progress' => DocumentDraftingRequest::where('client_id', auth()->id())->where('status', 'in_progress')->count(),
            'completed' => DocumentDraftingRequest::where('client_id', auth()->id())->where('status', 'completed')->count(),
            'cancelled' => DocumentDraftingRequest::where('client_id', auth()->id())->where('status', 'cancelled')->count(),
        ];

        return view('livewire.client.my-documents', [
            'requests' => $requests,
            'counts' => $counts,
        ])->layout('layouts.dashboard', ['title' => 'My Documents Request']);
    }
}
