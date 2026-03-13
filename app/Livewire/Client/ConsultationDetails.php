<?php

namespace App\Livewire\Client;

use App\Models\Consultation;
use App\Models\ConsultationDocument;
use App\Services\DeadlineCalculationService;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConsultationDetails extends Component
{
    use WithFileUploads;
    
    public Consultation $consultation;
    public $cancelReason = '';
    public $showCancelModal = false;
    public $showDeclineQuoteModal = false;
    public $showAcceptQuoteModal = false;
    public $showDeleteDocumentModal = false;
    public $documentToDelete = null;
    
    // Reschedule modal
    public $showRescheduleModal = false;
    public $selectedDate = null;
    public $availableSlots = [];
    public $selectedSlot = null;
    public $rescheduleReason = '';
    public $declineReason = '';
    public $showDeclineModal = false;
    
    // Document upload
    public $documents = [];
    public $uploadedDocuments = [];
    public $deletedDocuments = [];

    public function mount($id)
    {
        $this->consultation = Consultation::with([
            'client', 
            'lawyer.lawyerProfile', 
            'transaction', 
            'serviceRequests.requester',
            'activeDocuments.uploader',
            'review'
        ])->findOrFail($id);

        // Authorization check
        if ($this->consultation->client_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }
        
        // Load documents if document_review type
        $this->loadDocuments();
    }

    public function acceptQuote(DeadlineCalculationService $deadlineService)
    {
        if ($this->consultation->status !== 'awaiting_quote_approval') {
            session()->flash('error', 'Invalid consultation status.');
            $this->showAcceptQuoteModal = false;
            return;
        }

        // Check if this is a free consultation
        $isFree = empty($this->consultation->quoted_price) || $this->consultation->quoted_price == 0;

        if ($isFree) {
            // For free consultations, skip payment
            // Document reviews go to in_progress, others go to scheduled
            $status = $this->consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled';
            
            $this->consultation->update([
                'status' => $status,
                'quote_accepted_at' => now(),
                'accepted_at' => now(),
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

            // Send notification to lawyer about quote acceptance
            $this->consultation->lawyer->notify(new \App\Notifications\QuoteAccepted($this->consultation));

            $message = $this->consultation->consultation_type === 'document_review'
                ? 'Free document review accepted! The lawyer will start reviewing your document.'
                : 'Free consultation accepted! The lawyer will contact you to schedule the session.';
            session()->flash('success', $message);
            $this->showAcceptQuoteModal = false;
            
            // Refresh and stay on page
            $this->consultation->refresh();
            return;
        }

        // For paid consultations, proceed with payment flow
        // Validate if client can still pay (enough time before session)
        $canPayCheck = $deadlineService->canClientPay($this->consultation);
        if (!$canPayCheck['can_pay']) {
            session()->flash('error', $canPayCheck['reason']);
            $this->showAcceptQuoteModal = false;
            return;
        }

        // Calculate payment deadline
        $paymentDeadline = $deadlineService->calculatePaymentDeadline($this->consultation);

        $this->consultation->update([
            'status' => 'payment_pending',
            'quote_accepted_at' => now(),
            'accepted_at' => now(),
            'payment_deadline' => $paymentDeadline,
            'payment_deadline_calculated' => $paymentDeadline,
        ]);

        // Send notification to lawyer about quote acceptance
        $this->consultation->lawyer->notify(new \App\Notifications\QuoteAccepted($this->consultation));

        // Get time remaining for flash message
        $timeRemaining = $deadlineService->getTimeRemaining($paymentDeadline);
        
        session()->flash('success', sprintf(
            'Quote accepted! Please complete payment within %s.',
            $timeRemaining['formatted']
        ));
        
        $this->showAcceptQuoteModal = false;
        
        return redirect()->route('payment.checkout', $this->consultation);
    }

    public function declineQuote()
    {
        if ($this->consultation->status !== 'awaiting_quote_approval') {
            session()->flash('error', 'Invalid consultation status.');
            $this->showDeclineQuoteModal = false;
            return;
        }

        $this->consultation->update([
            'status' => 'declined',
            'decline_reason' => 'Client declined the quote',
        ]);

        // Send notification to lawyer about quote decline
        $this->consultation->lawyer->notify(new \App\Notifications\QuoteDeclined($this->consultation));

        session()->flash('success', 'Quote declined. You can search for other lawyers.');
        $this->showDeclineQuoteModal = false;
        
        return redirect()->route('client.consultations');
    }

    public function cancelConsultation()
    {
        if (!in_array($this->consultation->status, ['pending', 'payment_pending', 'scheduled', 'awaiting_quote_approval'])) {
            session()->flash('error', 'Cannot cancel this consultation.');
            $this->showCancelModal = false;
            return;
        }

        $this->consultation->update([
            'status' => 'cancelled',
            'cancel_reason' => $this->cancelReason ?: 'Cancelled by client',
        ]);

        // Create refund based on cancellation policy
        if ($this->consultation->transaction && in_array($this->consultation->transaction->status, ['completed', 'captured'])) {
            try {
                $refundService = app(\App\Services\RefundService::class);
                
                // Calculate refund amount based on timing
                $refundCalculation = $refundService->calculateCancellationRefund($this->consultation, 'client');
                
                if ($refundCalculation['refund_type'] !== 'none') {
                    $refundService->createAutoRefund(
                        $this->consultation->transaction,
                        'client_cancelled',
                        sprintf(
                            'Client cancelled consultation. %d%% refund issued based on cancellation policy. Reason: %s',
                            $refundCalculation['refund_percentage'],
                            $this->cancelReason ?: 'Cancelled by client'
                        ),
                        $refundCalculation['refund_amount'],
                        $refundCalculation['refund_type']
                    );
                    
                    \Log::info('Auto-refund created for client cancellation', [
                        'consultation_id' => $this->consultation->id,
                        'transaction_id' => $this->consultation->transaction->id,
                        'refund_percentage' => $refundCalculation['refund_percentage'],
                        'refund_amount' => $refundCalculation['refund_amount'],
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to create auto-refund for client cancellation', [
                    'consultation_id' => $this->consultation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        session()->flash('success', 'Consultation cancelled successfully.');
        $this->showCancelModal = false;
        
        return redirect()->route('client.consultations');
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
    
    // ==========================================
    // LAWYER-INITIATED SERVICE OFFER HANDLING
    // ==========================================
    
    public function acceptOffer()
    {
        // This method handles both awaiting_quote_approval and pending_client_acceptance
        if (!in_array($this->consultation->status, ['awaiting_quote_approval', 'pending_client_acceptance'])) {
            session()->flash('error', 'Invalid consultation status.');
            $this->showAcceptQuoteModal = false;
            return;
        }
        
        try {
            // If free service, activate immediately
            if ($this->consultation->quoted_price == 0) {
                $this->consultation->update([
                    'status' => $this->consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled',
                    'accepted_at' => now(),
                    'quote_accepted_at' => now(),
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
                
                if ($this->consultation->consultation_type === 'document_review') {
                    $this->consultation->update(['started_at' => now()]);
                }
                
                // Notify lawyer
                if ($this->consultation->initiated_by === 'lawyer') {
                    $this->consultation->lawyer->notify(new \App\Notifications\ServiceOfferAccepted($this->consultation));
                } else {
                    $this->consultation->lawyer->notify(new \App\Notifications\QuoteAccepted($this->consultation));
                }
                
                session()->flash('success', 'Service offer accepted! The service is now active.');
                
                // Refresh
                $this->consultation->refresh();
                $this->showAcceptQuoteModal = false;
            } else {
                // Paid service - set payment deadline and redirect to payment
                $deadlineService = app(\App\Services\DeadlineCalculationService::class);
                $paymentDeadline = $deadlineService->calculatePaymentDeadline($this->consultation);
                
                $this->consultation->update([
                    'status' => 'payment_pending',
                    'accepted_at' => now(),
                    'quote_accepted_at' => now(),
                    'payment_deadline' => $paymentDeadline,
                    'payment_deadline_calculated' => $paymentDeadline,
                ]);
                
                session()->flash('success', 'Service offer accepted! Please complete payment.');
                return redirect()->route('payment.checkout', $this->consultation);
            }
            
        } catch (\Exception $e) {
            \Log::error('Service offer acceptance failed', [
                'consultation_id' => $this->consultation->id,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Something went wrong. Please try again.');
            $this->showAcceptQuoteModal = false;
        }
    }
    
    public function declineOffer()
    {
        if ($this->consultation->status !== 'pending_client_acceptance' || $this->consultation->initiated_by !== 'lawyer') {
            session()->flash('error', 'Invalid service offer.');
            return;
        }
        
        try {
            $this->consultation->update([
                'status' => 'declined',
            ]);
            
            // Notify lawyer
            $this->consultation->lawyer->notify(new \App\Notifications\ServiceOfferDeclined($this->consultation));
            
            session()->flash('success', 'Service offer declined.');
            
            // Redirect back to consultation thread
            return redirect()->route('client.consultation-thread.details', $this->consultation->parent_consultation_id);
            
        } catch (\Exception $e) {
            \Log::error('Service offer decline failed', [
                'consultation_id' => $this->consultation->id,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }
    
    // ==========================================
    // Document Upload Methods (for document_review)
    // ==========================================
    
    public function loadDocuments()
    {
        // Load all documents (active and deleted) for all consultation types
        $this->uploadedDocuments = ConsultationDocument::where('consultation_id', $this->consultation->id)
            ->with(['uploader', 'deleter'])
            ->orderBy('uploaded_at', 'desc')
            ->get();
    }
    
    public function uploadDocuments(FileUploadService $fileService)
    {
        $this->validate([
            'documents.*' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);
        
        if (empty($this->documents)) {
            session()->flash('error', 'Please select at least one file to upload.');
            return;
        }
        
        try {
            $uploadedCount = 0;
            
            foreach ($this->documents as $document) {
                // Upload to S3 private bucket
                $fileData = $fileService->uploadPrivate(
                    $document,
                    'consultation-documents/' . $this->consultation->id
                );
                
                // Save to database
                ConsultationDocument::create([
                    'consultation_id' => $this->consultation->id,
                    'uploaded_by' => auth()->id(),
                    'original_filename' => $fileData['original_name'],
                    'stored_filename' => $fileData['encrypted_name'],
                    'file_path' => $fileData['path'],
                    'file_size' => $fileData['size'],
                    'mime_type' => $fileData['mime_type'],
                    'uploaded_at' => now(),
                ]);
                
                $uploadedCount++;
            }
            
            // Notify lawyer
            $this->consultation->lawyer->notify(
                new \App\Notifications\DocumentsUploaded($this->consultation, $uploadedCount)
            );
            
            // Reset file input
            $this->reset('documents');
            
            // Reload the page to show new documents
            session()->flash('success', "{$uploadedCount} document(s) uploaded successfully!");
            return redirect()->route('client.consultation.details', $this->consultation->id);
            
        } catch (\Exception $e) {
            \Log::error('Document upload failed', [
                'consultation_id' => $this->consultation->id,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Upload failed. Please try again.');
        }
    }
    
    public function confirmDelete($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->showDeleteDocumentModal = true;
    }
    
    public function deleteDocument(FileUploadService $fileService)
    {
        if (!$this->documentToDelete) {
            session()->flash('error', 'No document selected.');
            $this->showDeleteDocumentModal = false;
            return;
        }
        
        try {
            $document = ConsultationDocument::where('consultation_id', $this->consultation->id)
                ->where('id', $this->documentToDelete)
                ->whereNull('deleted_at')
                ->firstOrFail();
            
            // Soft delete in database
            $document->update([
                'deleted_at' => now(),
                'deleted_by' => auth()->id(),
            ]);
            
            // Notify lawyer
            $this->consultation->lawyer->notify(
                new \App\Notifications\DocumentDeleted($this->consultation, $document->original_filename)
            );
            
            // Reset modal state
            $this->showDeleteDocumentModal = false;
            $this->documentToDelete = null;
            
            // Reload the page to show updated list
            session()->flash('success', 'Document deleted successfully.');
            return redirect()->route('client.consultation.details', $this->consultation->id);
            
        } catch (\Exception $e) {
            \Log::error('Document deletion failed', [
                'document_id' => $this->documentToDelete,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Deletion failed. Please try again.');
            $this->showDeleteDocumentModal = false;
            $this->documentToDelete = null;
        }
    }
    
    public function getDocumentDownloadUrl($documentId, FileUploadService $fileService)
    {
        try {
            $document = ConsultationDocument::where('consultation_id', $this->consultation->id)
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
            'selectedDate' => 'required|date',
            'selectedSlot' => 'required',
            'rescheduleReason' => 'required|string|min:10|max:500',
        ], [
            'selectedDate.required' => 'Please select a date.',
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

    public function openDeclineModal()
    {
        $this->showDeclineModal = true;
        $this->declineReason = '';
    }

    public function declineReschedule()
    {
        $this->validate([
            'declineReason' => 'required|string|min:10|max:500',
        ], [
            'declineReason.required' => 'Please provide a reason for declining.',
            'declineReason.min' => 'Reason must be at least 10 characters.',
        ]);

        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->declineReschedule(
                $this->consultation,
                auth()->user(),
                $this->declineReason
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->showDeclineModal = false;
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
        
        return view('livewire.client.consultation-details', [
            'pendingServiceRequests' => $pendingServiceRequests,
            'pendingDocumentRequests' => $pendingDocumentRequests,
        ])->layout('layouts.dashboard', ['title' => 'Consultation Details']);
    }
}
