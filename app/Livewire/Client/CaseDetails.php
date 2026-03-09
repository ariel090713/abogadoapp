<?php

namespace App\Livewire\Client;

use App\Models\Consultation;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CaseDetails extends Component
{
    use AuthorizesRequests;

    public $caseId;
    public $case;
    public $sessions;
    public $caseStatus;
    public $totalSessions;
    public $completedSessions;
    public $timeline = [];
    public $allDocuments = [];

    public function mount($id)
    {
        $this->caseId = $id;
        $this->case = Consultation::with([
            'client', 
            'lawyer', 
            'childConsultations.serviceRequests',
            'serviceRequests'
        ])->findOrFail($id);

        // Authorization check
        if ($this->case->client_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this case.');
        }

        // Get all sessions (main consultation + follow-ups)
        $this->sessions = collect([$this->case])
            ->merge($this->case->childConsultations)
            ->sortBy('created_at');

        // Calculate case statistics
        $this->totalSessions = $this->sessions->count();
        $this->completedSessions = $this->sessions->where('status', 'completed')->count();

        // Determine overall case status
        $this->caseStatus = $this->determineCaseStatus();
        
        // Build timeline
        $this->buildTimeline();
        
        // Load all documents from all sessions
        $this->loadAllDocuments();
    }
    
    public function loadAllDocuments()
    {
        $sessionIds = $this->sessions->pluck('id');
        
        // Get documents from consultation_documents table
        $consultationDocuments = \App\Models\ConsultationDocument::whereIn('consultation_id', $sessionIds)
            ->with(['consultation', 'uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get()
            ->toBase(); // Convert to base Collection to avoid Eloquent Collection methods
        
        // Get reviewed documents from completed sessions (stored in consultations table)
        $reviewedDocuments = collect();
        foreach ($this->sessions as $session) {
            if ($session->reviewed_document_path && $session->status === 'completed') {
                $reviewedDocuments->push((object)[
                    'id' => 'reviewed_' . $session->id,
                    'consultation_id' => $session->id,
                    'original_filename' => 'Reviewed Document',
                    'file_path' => $session->reviewed_document_path,
                    'file_size' => 0,
                    'uploaded_at' => $session->completed_at ?? $session->updated_at,
                    'deleted_at' => $session->reviewed_document_deleted_at,
                    'uploader' => $session->lawyer,
                    'consultation' => $session,
                    'is_reviewed_document' => true,
                ]);
            }
        }
        
        // Merge and sort by date
        $this->allDocuments = $consultationDocuments->merge($reviewedDocuments)
            ->sortByDesc(function($doc) {
                return $doc->uploaded_at;
            })
            ->values();
    }
    
    public function getDocumentDownloadUrl($documentId)
    {
        try {
            // Check if it's a reviewed document
            if (str_starts_with($documentId, 'reviewed_')) {
                $consultationId = str_replace('reviewed_', '', $documentId);
                $consultation = Consultation::findOrFail($consultationId);
                
                if (!$consultation->reviewed_document_path) {
                    throw new \Exception('Document not found');
                }
                
                $fileService = app(\App\Services\FileUploadService::class);
                $url = $fileService->getPrivateUrl($consultation->reviewed_document_path, 60);
                
                $this->dispatch('open-url', url: $url);
            } else {
                // Regular consultation document
                $document = \App\Models\ConsultationDocument::where('id', $documentId)
                    ->whereNull('deleted_at')
                    ->firstOrFail();
                
                $fileService = app(\App\Services\FileUploadService::class);
                $url = $fileService->getPrivateUrl($document->file_path, 60);
                
                $this->dispatch('open-url', url: $url);
            }
            
        } catch (\Exception $e) {
            \Log::error('Document download failed', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Download failed. Please try again.');
        }
    }
    
    public function redirectToConsultation($consultationId)
    {
        return $this->redirect(route('client.consultation.details', $consultationId));
    }
    
    public function acceptServiceOffer($offerId)
    {
        $offer = Consultation::findOrFail($offerId);
        
        // Verify this is a pending offer for this client
        if ($offer->client_id !== auth()->id() || $offer->status !== 'pending_client_acceptance') {
            session()->flash('error', 'Invalid service offer.');
            return;
        }
        
        try {
            // If free service, activate immediately
            if ($offer->quoted_price == 0) {
                $offer->update([
                    'status' => $offer->consultation_type === 'document_review' ? 'in_progress' : 'scheduled',
                    'accepted_at' => now(),
                    'quote_accepted_at' => now(),
                ]);
                
                // Create a free transaction record for tracking
                \App\Models\Transaction::create([
                    'user_id' => $offer->client_id,
                    'lawyer_id' => $offer->lawyer_id,
                    'consultation_id' => $offer->id,
                    'type' => 'consultation_payment',
                    'amount' => 0,
                    'platform_fee' => 0,
                    'lawyer_payout' => 0,
                    'status' => 'completed',
                    'payment_method' => 'free',
                    'processed_at' => now(),
                ]);
                
                if ($offer->consultation_type === 'document_review') {
                    $offer->update(['started_at' => now()]);
                }
                
                // Notify lawyer
                $offer->lawyer->notify(new \App\Notifications\ServiceOfferAccepted($offer));
                
                session()->flash('success', 'Service offer accepted! The service is now active.');
            } else {
                // Paid service - redirect to payment
                $offer->update([
                    'status' => 'payment_pending',
                    'accepted_at' => now(),
                    'quote_accepted_at' => now(),
                ]);
                
                session()->flash('success', 'Service offer accepted! Please complete payment.');
                return redirect()->route('payment.checkout', $offer);
            }
            
            // Refresh the page data
            $this->mount($this->caseId);
            
        } catch (\Exception $e) {
            \Log::error('Service offer acceptance failed', [
                'offer_id' => $offerId,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }
    
    public function declineServiceOffer($offerId)
    {
        $offer = Consultation::findOrFail($offerId);
        
        // Verify this is a pending offer for this client
        if ($offer->client_id !== auth()->id() || $offer->status !== 'pending_client_acceptance') {
            session()->flash('error', 'Invalid service offer.');
            return;
        }
        
        try {
            $offer->update([
                'status' => 'declined',
            ]);
            
            // Notify lawyer
            $offer->lawyer->notify(new \App\Notifications\ServiceOfferDeclined($offer));
            
            session()->flash('success', 'Service offer declined.');
            
            // Refresh the page data
            $this->mount($this->caseId);
            
        } catch (\Exception $e) {
            \Log::error('Service offer decline failed', [
                'offer_id' => $offerId,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    private function determineCaseStatus()
    {
        if ($this->case->status === 'cancelled') {
            return 'cancelled';
        }

        if ($this->completedSessions === $this->totalSessions) {
            return 'completed';
        }

        $hasActiveSession = $this->sessions->whereIn('status', ['in_progress', 'scheduled', 'pending'])->count() > 0;
        
        return $hasActiveSession ? 'active' : 'completed';
    }

    private function buildTimeline()
    {
        $events = collect();

        // Add all sessions to timeline
        foreach ($this->sessions as $session) {
            $events->push([
                'type' => 'session_created',
                'date' => $session->created_at,
                'session' => $session,
                'data' => $session
            ]);

            if ($session->scheduled_at) {
                $events->push([
                    'type' => 'session_scheduled',
                    'date' => $session->scheduled_at,
                    'session' => $session,
                    'data' => $session
                ]);
            }

            if ($session->status === 'completed' && $session->completed_at) {
                $events->push([
                    'type' => 'session_completed',
                    'date' => $session->completed_at,
                    'session' => $session,
                    'data' => $session
                ]);
            }

            // Add service requests for this session
            foreach ($session->serviceRequests as $request) {
                $events->push([
                    'type' => 'follow_up_requested',
                    'date' => $request->created_at,
                    'session' => $session,
                    'data' => $request
                ]);

                if ($request->status === 'accepted') {
                    $events->push([
                        'type' => 'follow_up_accepted',
                        'date' => $request->updated_at,
                        'session' => $session,
                        'data' => $request
                    ]);
                } elseif ($request->status === 'declined') {
                    $events->push([
                        'type' => 'follow_up_declined',
                        'date' => $request->updated_at,
                        'session' => $session,
                        'data' => $request
                    ]);
                } elseif ($request->status === 'cancelled') {
                    $events->push([
                        'type' => 'follow_up_cancelled',
                        'date' => $request->updated_at,
                        'session' => $session,
                        'data' => $request
                    ]);
                }
            }
        }

        $this->timeline = $events->sortBy('date')->values()->all();
    }

    public function render()
    {
        return view('livewire.client.case-details')
            ->layout('layouts.dashboard', ['title' => 'Consultation Thread Details']);
    }
}
