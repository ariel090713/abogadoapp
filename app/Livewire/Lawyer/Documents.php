<?php

namespace App\Livewire\Lawyer;

use App\Models\LawyerDocumentService;
use Livewire\Component;
use Livewire\WithPagination;

class Documents extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // all, active, inactive
    public $openDropdown = null;
    public $showDeleteModal = false; // Show delete confirmation modal
    public $documentToDelete = null; // Track document to delete

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleDropdown($documentId)
    {
        $this->openDropdown = $this->openDropdown === $documentId ? null : $documentId;
    }

    public function confirmDelete($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->showDeleteModal = true;
    }

    public function deleteDocument()
    {
        if (!$this->documentToDelete) {
            return;
        }

        $document = LawyerDocumentService::where('lawyer_id', auth()->id())
            ->findOrFail($this->documentToDelete);
        
        // Check if there are pending requests
        if ($document->requests()->whereIn('status', ['pending_payment', 'paid', 'in_progress'])->exists()) {
            session()->flash('error', 'Cannot delete document with pending requests');
            $this->documentToDelete = null;
            $this->showDeleteModal = false;
            return;
        }

        $document->delete();
        session()->flash('success', 'Document deleted successfully');
        
        $this->documentToDelete = null;
        $this->showDeleteModal = false;
    }

    public function toggleStatus($documentId)
    {
        $document = LawyerDocumentService::where('lawyer_id', auth()->id())
            ->findOrFail($documentId);
        
        $document->update([
            'is_active' => !$document->is_active
        ]);

        session()->flash('success', 'Document status updated successfully');
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filter = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $query = LawyerDocumentService::where('lawyer_id', auth()->id())
            ->with('template');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filter === 'inactive') {
            $query->where('is_active', false);
        }

        $documents = $query->latest()->paginate(10);

        return view('livewire.lawyer.documents', [
            'documents' => $documents
        ])->layout('layouts.dashboard', ['title' => 'Documents Forms']);
    }
}
