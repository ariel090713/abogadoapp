<?php

namespace App\Livewire\Lawyer;

use App\Models\DocumentDraftingRequest;
use App\Services\FileUploadService;
use App\Services\DeadlineCalculationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentRequestDetails extends Component
{
    use WithFileUploads;

    public DocumentDraftingRequest $request;
    public $completedDocument;
    public $lawyerNotes = '';

    public function mount($id)
    {
        $this->request = DocumentDraftingRequest::with(['client', 'service'])
            ->where('lawyer_id', auth()->id())
            ->findOrFail($id);
        
        $this->lawyerNotes = $this->request->lawyer_notes ?? '';
    }

    public function startWork(DeadlineCalculationService $deadlineService)
    {
        if ($this->request->status !== 'paid') {
            session()->flash('error', 'Can only start work on paid requests');
            return;
        }

        try {
            // Calculate completion deadline based on service estimated days
            $completionDeadline = $deadlineService->calculateDocumentCompletionDeadline(
                $this->request->service->estimated_completion_days
            );

            $this->request->update([
                'status' => 'in_progress',
                'started_at' => now(),
                'completion_deadline' => $completionDeadline,
            ]);

            session()->flash('success', 'Work started! Please complete by ' . $completionDeadline->format('M d, Y'));
            
            // Send notification to client
            $this->request->client->notify(new \App\Notifications\DocumentWorkStarted($this->request));
            
        } catch (\Exception $e) {
            \Log::error('Failed to start document work', [
                'request_id' => $this->request->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to start work. Please try again.');
        }
    }

    public function completeDocument(FileUploadService $fileService)
    {
        $this->validate([
            'completedDocument' => 'required|file|max:10240|mimes:pdf,doc,docx',
            'lawyerNotes' => 'nullable|string|max:1000',
        ]);

        if (!in_array($this->request->status, ['paid', 'in_progress'])) {
            session()->flash('error', 'Invalid request status');
            return;
        }

        try {
            // Upload document to private bucket
            $fileData = $fileService->uploadPrivate(
                $this->completedDocument,
                'completed-documents'
            );

            $this->request->update([
                'status' => 'completed',
                'draft_document_path' => $fileData['path'],
                'lawyer_notes' => $this->lawyerNotes,
                'completed_at' => now(),
            ]);

            // Send notification to client
            $this->request->client->notify(new \App\Notifications\DocumentCompleted($this->request));

            session()->flash('success', 'Document completed and uploaded successfully!');
            
            // TODO: Send notification to client
            
            $this->reset('completedDocument');
            
        } catch (\Exception $e) {
            \Log::error('Failed to complete document', [
                'request_id' => $this->request->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to upload document. Please try again.');
        }
    }

    public function downloadExistingDocument(FileUploadService $fileService)
    {
        if (!$this->request->draft_document_path) {
            session()->flash('error', 'No document available');
            return;
        }

        try {
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

    public function startRevision()
    {
        if ($this->request->status !== 'revision_requested') {
            session()->flash('error', 'This request is not pending revision');
            return;
        }

        try {
            $this->request->update([
                'status' => 'in_progress',
            ]);

            session()->flash('success', 'Revision work started. Please upload the revised document.');
            
            // Send notification to client
            $this->request->client->notify(new \App\Notifications\DocumentRevisionStarted($this->request));
            
        } catch (\Exception $e) {
            \Log::error('Failed to start revision', [
                'request_id' => $this->request->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to start revision. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.lawyer.document-request-details')
            ->layout('layouts.dashboard', ['title' => 'Document Request Details']);
    }
}
