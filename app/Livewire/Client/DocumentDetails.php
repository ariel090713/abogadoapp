<?php

namespace App\Livewire\Client;

use App\Models\DocumentDraftingRequest;
use App\Services\FileUploadService;
use Livewire\Component;

class DocumentDetails extends Component
{
    public DocumentDraftingRequest $request;
    public $revisionNotes = '';
    public $showRevisionModal = false;

    public function mount($id)
    {
        $this->request = DocumentDraftingRequest::with(['lawyer.lawyerProfile', 'service', 'review'])
            ->where('client_id', auth()->id())
            ->findOrFail($id);
    }

    public function downloadDocument(FileUploadService $fileService)
    {
        if (!$this->request->draft_document_path) {
            session()->flash('error', 'Document not available yet');
            return;
        }

        try {
            // Generate temporary signed URL (expires in 1 hour)
            $url = $fileService->getPrivateUrl($this->request->draft_document_path, 60);
            
            return redirect($url);
        } catch (\Exception $e) {
            \Log::error('Document download failed', [
                'request_id' => $this->request->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to download document');
        }
    }

    public function proceedToPayment()
    {
        if ($this->request->status !== 'pending_payment') {
            session()->flash('error', 'This request is not pending payment');
            return;
        }

        return redirect()->route('document.payment', $this->request->id);
    }

    public function requestRevision()
    {
        // Check if revisions are available
        if ($this->request->revisions_used >= $this->request->revisions_allowed) {
            session()->flash('error', 'No more revisions available for this document');
            return;
        }

        // Validate revision notes
        $this->validate([
            'revisionNotes' => 'required|string|max:1000',
        ]);

        try {
            // Update request status and increment revisions used
            $this->request->update([
                'status' => 'revision_requested',
                'revisions_used' => $this->request->revisions_used + 1,
                'revision_notes' => $this->revisionNotes,
            ]);

            // Send notification to lawyer
            $this->request->lawyer->notify(new \App\Notifications\DocumentRevisionRequested($this->request));

            session()->flash('success', 'Revision requested successfully! The lawyer will be notified.');
            
            // TODO: Send notification to lawyer
            
            $this->reset('revisionNotes', 'showRevisionModal');
            
        } catch (\Exception $e) {
            \Log::error('Failed to request revision', [
                'request_id' => $this->request->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to request revision. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.client.document-details')
            ->layout('layouts.dashboard', ['title' => 'Document Request Details']);
    }
}
