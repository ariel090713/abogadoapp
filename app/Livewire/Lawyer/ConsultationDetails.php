<?php

namespace App\Livewire\Lawyer;

use App\Models\Consultation;
use App\Services\DeadlineCalculationService;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConsultationDetails extends Component
{
    use WithFileUploads;
    
    public Consultation $consultation;
    public $declineReason = '';
    public $quotedPrice = '';
    public $quoteNotes = '';
    public $estimatedTurnaroundDays = 3; // Default 3 days
    public $showDeclineModal = false;
    public $showQuoteModal = false;
    public $showAcceptModal = false;
    public $showCompleteModal = false;
    public $showUpdateCompletionModal = false;
    public $showDeleteDocumentModal = false;
    public $showCancelOfferModal = false;
    
    // Reschedule modal
    public $showRescheduleModal = false;
    public $selectedDate = null;
    public $availableSlots = [];
    public $selectedSlot = null;
    public $rescheduleReason = '';
    public $rescheduleDeclineReason = ''; // Renamed to avoid conflict
    public $showDeclineRescheduleModal = false;
    public $completionNotes = '';
    public $reviewedDocument;
    public $updateNotes = '';
    public $updateDocument;
    
    // Document viewing
    public $uploadedDocuments = [];

    public function mount($id)
    {
        $this->consultation = Consultation::with(['client', 'lawyer.lawyerProfile', 'transaction', 'serviceRequests.requester', 'activeDocuments.uploader'])
            ->findOrFail($id);

        // Authorization check
        if ($this->consultation->lawyer_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }
        
        // Load documents if document_review type
        $this->loadDocuments();
    }
    
    public function loadDocuments()
    {
        // Load all documents (active and deleted) for all consultation types
        $this->uploadedDocuments = \App\Models\ConsultationDocument::where('consultation_id', $this->consultation->id)
            ->with(['uploader', 'deleter'])
            ->orderBy('uploaded_at', 'desc')
            ->get();
    }
    
    public function getDocumentDownloadUrl($documentId, FileUploadService $fileService)
    {
        try {
            $document = \App\Models\ConsultationDocument::where('consultation_id', $this->consultation->id)
                ->where('id', $documentId)
                ->whereNull('deleted_at')
                ->firstOrFail();
            
            // Generate signed URL (1 hour expiry)
            $url = $fileService->getPrivateUrl($document->file_path, 60);
            
            // Dispatch event to open in new tab
            $this->dispatch('open-url', url: $url);
            
        } catch (\Exception $e) {
            \Log::error('Document download failed', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Download failed. Please try again.');
        }
    }

    public function acceptRequest(DeadlineCalculationService $deadlineService)
    {
        if ($this->consultation->status !== 'pending') {
            session()->flash('error', 'Invalid consultation status.');
            $this->showAcceptModal = false;
            return;
        }

        // Check if lawyer can still accept (enough time remaining)
        $canAccept = $deadlineService->canLawyerAccept($this->consultation);
        if (!$canAccept['can_accept']) {
            session()->flash('error', $canAccept['reason']);
            $this->showAcceptModal = false;
            return;
        }

        // Calculate payment deadline
        $this->consultation->accepted_at = now();
        $paymentDeadline = $deadlineService->calculatePaymentDeadline($this->consultation);

        $this->consultation->update([
            'status' => 'payment_pending',
            'accepted_at' => now(),
            'payment_deadline' => $paymentDeadline,
            'payment_deadline_calculated' => $paymentDeadline,
        ]);

        $this->consultation->client->notify(new \App\Notifications\ConsultationAccepted($this->consultation));

        session()->flash('success', 'Consultation request accepted! Client will be notified.');
        $this->showAcceptModal = false;
        
        // Refresh consultation
        $this->consultation->refresh();
    }

    public function declineRequest()
    {
        if ($this->consultation->status !== 'pending') {
            session()->flash('error', 'Invalid consultation status.');
            $this->showDeclineModal = false;
            return;
        }

        $this->validate([
            'declineReason' => 'required|string|min:10|max:500',
        ]);

        $this->consultation->update([
            'status' => 'declined',
            'decline_reason' => $this->declineReason,
        ]);

        // Create automatic refund if payment was made
        if ($this->consultation->transaction && in_array($this->consultation->transaction->status, ['completed', 'captured'])) {
            try {
                $refundService = app(\App\Services\RefundService::class);
                $refundService->createAutoRefund(
                    $this->consultation->transaction,
                    'lawyer_declined',
                    'Lawyer declined the consultation request. Full refund issued automatically. Reason: ' . $this->declineReason
                );
                
                \Log::info('Auto-refund created for declined consultation', [
                    'consultation_id' => $this->consultation->id,
                    'transaction_id' => $this->consultation->transaction->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create auto-refund for declined consultation', [
                    'consultation_id' => $this->consultation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->consultation->client->notify(new \App\Notifications\ConsultationDeclined($this->consultation));

        session()->flash('success', 'Consultation request declined.');
        $this->showDeclineModal = false;
        
        return redirect()->route('lawyer.consultations');
    }

    public function provideQuote(DeadlineCalculationService $deadlineService)
    {
        if ($this->consultation->status !== 'pending') {
            session()->flash('error', 'Invalid consultation status.');
            $this->showQuoteModal = false;
            return;
        }

        $rules = [
            'quotedPrice' => 'nullable|numeric|min:0',
            'quoteNotes' => 'required|string|min:10|max:500',
        ];
        
        // Add turnaround days validation for document reviews
        if ($this->consultation->consultation_type === 'document_review') {
            $rules['estimatedTurnaroundDays'] = 'required|integer|min:1|max:30';
        }
        
        $this->validate($rules);

        // Check if lawyer can still provide quote (enough time remaining)
        $canAccept = $deadlineService->canLawyerAccept($this->consultation);
        if (!$canAccept['can_accept']) {
            session()->flash('error', $canAccept['reason']);
            $this->showQuoteModal = false;
            return;
        }

        // If price is empty or zero, treat as free consultation
        $price = empty($this->quotedPrice) ? 0 : $this->quotedPrice;
        $isFree = $price == 0;

        if ($isFree) {
            // For free consultations, auto-accept
            // Document reviews go to in_progress, others go to scheduled
            $status = $this->consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled';
            
            $this->consultation->update([
                'status' => $status,
                'quoted_price' => 0,
                'quote_notes' => $this->quoteNotes,
                'quote_provided_at' => now(),
                'quote_accepted_at' => now(),
                'accepted_at' => now(),
                // Don't update rate - keep original minimum price
                'platform_fee' => 0,
                'total_amount' => 0,
                'estimated_turnaround_days' => $this->consultation->consultation_type === 'document_review' ? $this->estimatedTurnaroundDays : null,
                'started_at' => $this->consultation->consultation_type === 'document_review' ? now() : null,
            ]);
            
            // Create a free transaction record for tracking
            \App\Models\Transaction::create([
                'user_id' => $this->consultation->client_id,
                'lawyer_id' => $this->consultation->lawyer_id,
                'consultation_id' => $this->consultation->id,
                'type' => 'consultation_payment',
                'amount' => 0,
                'platform_fee' => 0,
                'lawyer_payout' => 0,
                'status' => 'completed',
                'payment_method' => 'free',
                'processed_at' => now(),
            ]);

            // Send notification to client about free consultation
            $this->consultation->client->notify(new \App\Notifications\ConsultationQuoted($this->consultation));

            $message = $this->consultation->consultation_type === 'document_review' 
                ? 'Free document review accepted! You can now start reviewing the document.' 
                : 'Free consultation offer sent and automatically accepted! You can now schedule the session.';
            session()->flash('success', $message);
        } else {
            // For paid consultations, send quote for client approval
            $this->consultation->update([
                'status' => 'awaiting_quote_approval',
                'quoted_price' => $price,
                'quote_notes' => $this->quoteNotes,
                'quote_provided_at' => now(),
                // Don't update rate - keep original minimum price
                'platform_fee' => 0,
                'total_amount' => $price,
                'estimated_turnaround_days' => $this->consultation->consultation_type === 'document_review' ? $this->estimatedTurnaroundDays : null,
            ]);

            // Calculate quote response deadline
            $this->consultation->quote_deadline = $deadlineService->calculateQuoteResponseDeadline($this->consultation);
            $this->consultation->save();

            // Send notification to client about quote
            $this->consultation->client->notify(new \App\Notifications\ConsultationQuoted($this->consultation));

            session()->flash('success', 'Quote sent to client successfully!');
        }

        // Reset form and close modal
        $this->quotedPrice = '';
        $this->quoteNotes = '';
        $this->estimatedTurnaroundDays = 3;
        $this->showQuoteModal = false;
        
        // Refresh consultation
        $this->consultation->refresh();
    }

    public function completeConsultation(FileUploadService $fileService)
    {
        // Validate status
        if (!in_array($this->consultation->status, ['scheduled', 'in_progress'])) {
            session()->flash('error', 'Invalid consultation status.');
            $this->showCompleteModal = false;
            return;
        }

        // Validate - completion notes are now required
        $this->validate([
            'completionNotes' => 'required|string|min:10|max:5000',
            'reviewedDocument' => 'nullable|file|max:10240|mimes:pdf,doc,docx',
        ], [
            'completionNotes.required' => 'Completion notes are required.',
            'completionNotes.min' => 'Please provide at least 10 characters of notes.',
            'completionNotes.max' => 'Completion notes cannot exceed 5000 characters.',
        ]);

        try {
            $updateData = [
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $this->completionNotes,
            ];

            // Upload reviewed document if provided
            if ($this->reviewedDocument) {
                $fileData = $fileService->uploadPrivate(
                    $this->reviewedDocument,
                    'reviewed-documents'
                );
                
                $updateData['reviewed_document_path'] = $fileData['path'];
            }

            $this->consultation->update($updateData);

            // Send notification to client
            $this->consultation->client->notify(new \App\Notifications\ConsultationCompleted($this->consultation));

            session()->flash('success', 'Consultation marked as completed! Client has been notified.');
            $this->showCompleteModal = false;
            
            // Refresh consultation
            $this->consultation->refresh();
            
        } catch (\Exception $e) {
            \Log::error('Consultation completion failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to complete consultation. Please try again.');
            $this->showCompleteModal = false;
        }
    }

    public function openUpdateModal()
    {
        // Pre-fill with existing notes
        $this->updateNotes = $this->consultation->completion_notes ?? '';
        $this->showUpdateCompletionModal = true;
    }

    public function updateCompletion(FileUploadService $fileService)
    {
        // Validate status
        if ($this->consultation->status !== 'completed') {
            session()->flash('error', 'Can only update completed consultations.');
            $this->showUpdateCompletionModal = false;
            return;
        }

        $this->validate([
            'updateNotes' => 'nullable|string|max:2000',
            'updateDocument' => 'nullable|file|max:10240|mimes:pdf,doc,docx',
        ]);

        try {
            $updateData = [
                'completion_updated_at' => now(),
            ];

            // Update notes if provided
            if (!empty($this->updateNotes)) {
                $updateData['completion_notes'] = $this->updateNotes;
            }

            // Upload new document if provided (replaces old one)
            if ($this->updateDocument) {
                // Move old document to deleted if exists
                if ($this->consultation->reviewed_document_path) {
                    $updateData['reviewed_document_deleted_path'] = $this->consultation->reviewed_document_path;
                    $updateData['reviewed_document_deleted_at'] = now();
                }

                $fileData = $fileService->uploadPrivate(
                    $this->updateDocument,
                    'reviewed-documents'
                );
                
                $updateData['reviewed_document_path'] = $fileData['path'];
            }

            $this->consultation->update($updateData);

            session()->flash('success', 'Completion updated successfully!');
            $this->showUpdateCompletionModal = false;
            $this->updateNotes = '';
            $this->updateDocument = null;
            
            // Refresh consultation
            $this->consultation->refresh();
            
        } catch (\Exception $e) {
            \Log::error('Completion update failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to update completion. Please try again.');
            $this->showUpdateCompletionModal = false;
        }
    }

    public function deleteReviewedDocument()
    {
        // Validate status
        if ($this->consultation->status !== 'completed') {
            session()->flash('error', 'Can only delete documents from completed consultations.');
            $this->showDeleteDocumentModal = false;
            return;
        }

        if (!$this->consultation->reviewed_document_path) {
            session()->flash('error', 'No document to delete.');
            $this->showDeleteDocumentModal = false;
            return;
        }

        try {
            // Soft delete - move to deleted path
            $this->consultation->update([
                'reviewed_document_deleted_path' => $this->consultation->reviewed_document_path,
                'reviewed_document_deleted_at' => now(),
                'reviewed_document_path' => null,
                'completion_updated_at' => now(),
            ]);

            session()->flash('success', 'Document deleted successfully. You can upload a new one.');
            $this->showDeleteDocumentModal = false;
            
            // Refresh consultation
            $this->consultation->refresh();
            
        } catch (\Exception $e) {
            \Log::error('Document deletion failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to delete document. Please try again.');
            $this->showDeleteDocumentModal = false;
        }
    }

    // ==========================================
    // SERVICE REQUEST HANDLING
    // ==========================================

    public function acceptServiceRequest($requestId)
    {
        $request = \App\Models\ServiceRequest::findOrFail($requestId);
        
        // Verify this is for this consultation
        if ($request->consultation_id !== $this->consultation->id) {
            session()->flash('error', 'Invalid request.');
            return;
        }
        
        // Verify user can respond
        if ($request->requested_by === auth()->id()) {
            session()->flash('error', 'You cannot respond to your own request.');
            return;
        }
        
        try {
            \DB::transaction(function () use ($request) {
                // Update request status
                $request->update([
                    'status' => 'accepted',
                    'responded_by' => auth()->id(),
                    'responded_at' => now(),
                ]);
                
                // Get next session number
                $mainCase = $this->consultation->getMainCase();
                $nextSessionNumber = $mainCase->getTotalSessionsCount() + 1;
                
                // Create new consultation session
                $newConsultation = Consultation::create([
                    'parent_consultation_id' => $mainCase->id,
                    'session_number' => $nextSessionNumber,
                    'is_follow_up' => true,
                    'follow_up_type' => 'requested_session',
                    'client_id' => $this->consultation->client_id,
                    'lawyer_id' => $this->consultation->lawyer_id,
                    'consultation_type' => $request->service_type,
                    'title' => 'Follow-up: ' . $this->consultation->title,
                    'status' => $request->proposed_price ? 'payment_pending' : 'pending',
                    'rate' => $request->proposed_price ?? 0,
                    'total_amount' => $request->proposed_price ?? 0,
                    'quoted_price' => $request->proposed_price,
                    'scheduled_at' => $request->proposed_date,
                ]);
                
                // If price was proposed, set payment deadline
                if ($request->proposed_price) {
                    $deadlineService = app(\App\Services\DeadlineCalculationService::class);
                    $newConsultation->update([
                        'payment_deadline' => $deadlineService->calculatePaymentDeadline($newConsultation),
                    ]);
                }
                
                // Notify requester
                $request->requester->notify(new \App\Notifications\FollowUpAccepted($request, $newConsultation));
            });
            
            session()->flash('success', 'Follow-up request accepted. New session created.');
            
            // Refresh consultation and reload relationships
            $this->consultation->load(['serviceRequests', 'childConsultations']);
            
            // Dispatch browser event to refresh
            $this->dispatch('consultation-updated');
            
        } catch (\Exception $e) {
            \Log::error('Failed to accept service request', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to accept request. Please try again.');
        }
    }

    public function declineServiceRequest($requestId)
    {
        $request = \App\Models\ServiceRequest::findOrFail($requestId);
        
        // Verify this is for this consultation
        if ($request->consultation_id !== $this->consultation->id) {
            session()->flash('error', 'Invalid request.');
            return;
        }
        
        // Verify user can respond
        if ($request->requested_by === auth()->id()) {
            session()->flash('error', 'You cannot respond to your own request.');
            return;
        }
        
        $request->update([
            'status' => 'declined',
            'responded_by' => auth()->id(),
            'responded_at' => now(),
        ]);
        
        // Notify requester
        $request->requester->notify(new \App\Notifications\FollowUpDeclined($request));
        
        session()->flash('success', 'Follow-up request declined.');
        $this->consultation->refresh();
    }

    public function cancelOffer()
    {
        // Verify this is a pending offer initiated by the lawyer
        if ($this->consultation->status !== 'pending_client_acceptance') {
            session()->flash('error', 'This offer cannot be cancelled.');
            $this->showCancelOfferModal = false;
            return;
        }

        if ($this->consultation->initiated_by !== 'lawyer') {
            session()->flash('error', 'You can only cancel offers you initiated.');
            $this->showCancelOfferModal = false;
            return;
        }

        try {
            $this->consultation->update([
                'status' => 'cancelled',
                'cancel_reason' => 'Lawyer cancelled the service offer.',
            ]);

            // Create automatic refund if payment was made
            if ($this->consultation->transaction && in_array($this->consultation->transaction->status, ['completed', 'captured'])) {
                try {
                    $refundService = app(\App\Services\RefundService::class);
                    $refundService->createAutoRefund(
                        $this->consultation->transaction,
                        'lawyer_cancelled',
                        'Lawyer cancelled the service offer. Full refund issued automatically.'
                    );
                    
                    \Log::info('Auto-refund created for cancelled offer', [
                        'consultation_id' => $this->consultation->id,
                        'transaction_id' => $this->consultation->transaction->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to create auto-refund for cancelled offer', [
                        'consultation_id' => $this->consultation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Notify client
            $this->consultation->client->notify(new \App\Notifications\ServiceOfferCancelled($this->consultation));

            session()->flash('success', 'Service offer cancelled successfully.');
            $this->showCancelOfferModal = false;
            
            // Redirect to consultation thread details
            return redirect()->route('lawyer.consultation-thread.details', $this->consultation->parent_consultation_id);
            
        } catch (\Exception $e) {
            \Log::error('Failed to cancel offer', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to cancel offer. Please try again.');
            $this->showCancelOfferModal = false;
        }
    }

    // ==========================================
    // RESCHEDULE METHODS
    // ==========================================

    public function openRescheduleModal()
    {
        if (!$this->consultation->canBeRescheduled()) {
            session()->flash('error', 'This consultation cannot be rescheduled at this time.');
            return;
        }

        $this->showRescheduleModal = true;
        $this->selectedDate = null;
        $this->availableSlots = [];
        $this->selectedSlot = null;
        $this->rescheduleReason = '';
    }

    public function updatedSelectedDate($value)
    {
        if (!$value) {
            $this->availableSlots = [];
            return;
        }

        $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
        
        $this->availableSlots = $rescheduleService->getAvailableTimeSlots(
            $this->consultation->lawyer,
            \Carbon\Carbon::parse($value),
            $this->consultation->duration,
            $this->consultation->id  // Exclude current consultation from conflicts
        );

        $this->selectedSlot = null;
    }

    public function requestReschedule()
    {
        $this->validate([
            'selectedDate' => 'required|date|after:today',
            'selectedSlot' => 'required',
            'rescheduleReason' => 'required|string|min:10|max:500',
        ], [
            'selectedDate.required' => 'Please select a date.',
            'selectedDate.after' => 'Date must be in the future.',
            'selectedSlot.required' => 'Please select a time slot.',
            'rescheduleReason.required' => 'Please provide a reason for rescheduling.',
            'rescheduleReason.min' => 'Reason must be at least 10 characters.',
        ]);

        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $proposedSchedule = \Carbon\Carbon::parse($this->selectedDate . ' ' . $this->selectedSlot);
            
            $result = $rescheduleService->requestReschedule(
                $this->consultation,
                auth()->user(),
                $proposedSchedule,
                $this->rescheduleReason
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->showRescheduleModal = false;
                $this->consultation->refresh();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule request failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to request reschedule. Please try again.');
        }
    }

    public function approveReschedule()
    {
        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->approveReschedule(
                $this->consultation,
                auth()->user()
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->consultation->refresh();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule approval failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to approve reschedule. Please try again.');
        }
    }

    public function openDeclineRescheduleModal()
    {
        $this->showDeclineRescheduleModal = true;
        $this->rescheduleDeclineReason = '';
    }

    public function declineReschedule()
    {
        $this->validate([
            'rescheduleDeclineReason' => 'required|string|min:10|max:500',
        ], [
            'rescheduleDeclineReason.required' => 'Please provide a reason for declining.',
            'rescheduleDeclineReason.min' => 'Reason must be at least 10 characters.',
        ]);

        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->declineReschedule(
                $this->consultation,
                auth()->user(),
                $this->rescheduleDeclineReason
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->showDeclineRescheduleModal = false;
                $this->consultation->refresh();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule decline failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to decline reschedule. Please try again.');
        }
    }

    public function cancelRescheduleRequest()
    {
        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->cancelRescheduleRequest(
                $this->consultation,
                auth()->user()
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->consultation->refresh();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule cancellation failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to cancel reschedule request. Please try again.');
        }
    }


    public function render()
    {
        // Load pending requests
        $pendingServiceRequests = $this->consultation->pendingServiceRequests()
            ->with('requester')
            ->get();
        
        $pendingDocumentRequests = $this->consultation->pendingDocumentRequests()
            ->with('requester')
            ->get();
        
        return view('livewire.lawyer.consultation-details', [
            'pendingServiceRequests' => $pendingServiceRequests,
            'pendingDocumentRequests' => $pendingDocumentRequests,
        ])->layout('layouts.dashboard', ['title' => 'Consultation Details']);
    }
}
