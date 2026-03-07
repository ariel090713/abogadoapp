<?php

namespace App\Livewire\Client;

use App\Models\Consultation;
use App\Models\DocumentDraftingRequest;
use App\Models\Review;
use Livewire\Component;

class LeaveReview extends Component
{
    public $consultationId;
    public $documentRequestId;
    public $lawyerProfileId;
    public $existingReview;
    
    public $rating = 0;
    public $comment = '';
    public $isEditing = false;

    protected function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ];
    }

    public function mount($consultationId = null, $documentRequestId = null)
    {
        $this->consultationId = $consultationId;
        $this->documentRequestId = $documentRequestId;

        // Get lawyer profile ID
        if ($consultationId) {
            $consultation = Consultation::with('lawyer.lawyerProfile')
                ->where('client_id', auth()->id())
                ->findOrFail($consultationId);
            
            $this->lawyerProfileId = $consultation->lawyer->lawyerProfile->id;
            
            // Check for existing review
            $this->existingReview = Review::where('consultation_id', $consultationId)
                ->where('client_id', auth()->id())
                ->first();
        } elseif ($documentRequestId) {
            $documentRequest = DocumentDraftingRequest::with('lawyer.lawyerProfile')
                ->where('client_id', auth()->id())
                ->findOrFail($documentRequestId);
            
            $this->lawyerProfileId = $documentRequest->lawyer->lawyerProfile->id;
            
            // Check for existing review
            $this->existingReview = Review::where('document_request_id', $documentRequestId)
                ->where('client_id', auth()->id())
                ->first();
        }

        // Load existing review data if editing
        if ($this->existingReview) {
            $this->isEditing = true;
            $this->rating = $this->existingReview->rating;
            $this->comment = $this->existingReview->comment;
        }
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function submit()
    {
        $this->validate();

        try {
            if ($this->isEditing && $this->existingReview) {
                // Update existing review
                if (!$this->existingReview->canEdit()) {
                    session()->flash('error', 'This review can no longer be edited.');
                    return;
                }

                $this->existingReview->update([
                    'rating' => $this->rating,
                    'comment' => $this->comment,
                    'is_edited' => true,
                    'edited_at' => now(),
                ]);

                session()->flash('success', 'Review updated successfully!');
            } else {
                // Create new review
                Review::create([
                    'lawyer_profile_id' => $this->lawyerProfileId,
                    'client_id' => auth()->id(),
                    'consultation_id' => $this->consultationId,
                    'document_request_id' => $this->documentRequestId,
                    'rating' => $this->rating,
                    'comment' => $this->comment,
                    'published_at' => now(),
                ]);

                session()->flash('success', 'Thank you for your review!');
            }

            // Update lawyer rating
            $this->updateLawyerRating();

            // Redirect back
            if ($this->consultationId) {
                return redirect()->route('client.consultation.details', $this->consultationId);
            } else {
                return redirect()->route('client.documents');
            }
        } catch (\Exception $e) {
            \Log::error('Review submission failed', [
                'error' => $e->getMessage(),
                'consultation_id' => $this->consultationId,
                'document_request_id' => $this->documentRequestId,
            ]);
            
            session()->flash('error', 'An error occurred. Please try again.');
        }
    }

    private function updateLawyerRating()
    {
        $lawyerProfile = \App\Models\LawyerProfile::find($this->lawyerProfileId);
        
        if ($lawyerProfile) {
            $reviews = Review::where('lawyer_profile_id', $this->lawyerProfileId)->get();
            $lawyerProfile->rating = $reviews->avg('rating');
            $lawyerProfile->total_reviews = $reviews->count();
            $lawyerProfile->save();
        }
    }

    public function render()
    {
        return view('livewire.client.leave-review')
            ->layout('layouts.dashboard', ['title' => $this->isEditing ? 'Edit Review' : 'Leave a Review']);
    }
}
