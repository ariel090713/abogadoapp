<?php

namespace App\Livewire\Lawyer;

use App\Models\Consultation;
use App\Services\DeadlineCalculationService;
use Livewire\Component;
use Livewire\WithPagination;

class Consultations extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url]
    public $filter = 'pending'; // pending, payment_pending, scheduled, in_progress, completed, declined, cancelled, all
    public $search = ''; // Search query
    public $typeFilter = ''; // Filter by consultation type (chat, video, document_review)
    
    // Modal state
    public $showAcceptModal = false;
    public $showDeclineModal = false;
    public $showQuoteModal = false;
    public $selectedConsultationId = null;
    public $declineReason = '';
    public $quotedPrice = '';
    public $quoteNotes = '';

    public function render()
    {
        $query = Consultation::forLawyer(auth()->id())
            ->with(['client'])
            ->select([
                'consultations.*',
                'lawyer_response_deadline',
                'quote_deadline',
                'payment_deadline',
                'payment_deadline_calculated',
            ])
            ->orderBy('created_at', 'desc');

        // Apply status filter
        if ($this->filter === 'pending') {
            $query->pending();
        } elseif ($this->filter === 'payment_pending') {
            $query->whereIn('status', ['payment_pending', 'payment_processing', 'payment_failed']);
        } elseif ($this->filter === 'awaiting_quote') {
            $query->where('status', 'awaiting_quote_approval');
        } elseif ($this->filter === 'scheduled') {
            $query->where('status', 'scheduled');
        } elseif ($this->filter === 'in_progress') {
            $query->where('status', 'in_progress');
        } elseif ($this->filter === 'completed') {
            $query->completed();
        } elseif ($this->filter === 'cancelled') {
            $query->whereIn('status', ['cancelled', 'declined', 'expired', 'payment_failed']);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('client_notes', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function($clientQuery) {
                      $clientQuery->where('name', 'like', '%' . $this->search . '%')
                                  ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply type filter
        if ($this->typeFilter) {
            $query->where('consultation_type', $this->typeFilter);
        }

        $consultations = $query->paginate(12);

        $counts = [
            'all' => Consultation::forLawyer(auth()->id())->count(),
            'pending' => Consultation::forLawyer(auth()->id())->pending()->count(),
            'payment_pending' => Consultation::forLawyer(auth()->id())->whereIn('status', ['payment_pending', 'payment_processing', 'payment_failed'])->count(),
            'scheduled' => Consultation::forLawyer(auth()->id())->where('status', 'scheduled')->count(),
            'in_progress' => Consultation::forLawyer(auth()->id())->where('status', 'in_progress')->count(),
            'completed' => Consultation::forLawyer(auth()->id())->completed()->count(),
            'cancelled' => Consultation::forLawyer(auth()->id())->whereIn('status', ['cancelled', 'declined', 'expired', 'payment_failed'])->count(),
            'awaiting_quote' => Consultation::forLawyer(auth()->id())->where('status', 'awaiting_quote_approval')->count(),
        ];

        return view('livewire.lawyer.consultations', [
            'consultations' => $consultations,
            'counts' => $counts,
        ])->layout('layouts.dashboard', ['title' => 'Consultations']);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->typeFilter = '';
        $this->resetPage();
    }

    public function openAcceptModal($consultationId)
    {
        \Log::info('openAcceptModal called', ['consultationId' => $consultationId]);
        $this->selectedConsultationId = $consultationId;
        $this->showAcceptModal = true;
        \Log::info('Modal state', ['showAcceptModal' => $this->showAcceptModal, 'selectedId' => $this->selectedConsultationId]);
    }

    public function openDeclineModal($consultationId)
    {
        \Log::info('openDeclineModal called', ['consultationId' => $consultationId]);
        $this->selectedConsultationId = $consultationId;
        $this->showDeclineModal = true;
        $this->declineReason = '';
        \Log::info('Modal state', ['showDeclineModal' => $this->showDeclineModal, 'selectedId' => $this->selectedConsultationId]);
    }

    public function closeModals()
    {
        $this->showAcceptModal = false;
        $this->showDeclineModal = false;
        $this->showQuoteModal = false;
        $this->selectedConsultationId = null;
        $this->declineReason = '';
        $this->quotedPrice = '';
        $this->quoteNotes = '';
    }

    public function openQuoteModal($consultationId)
    {
        $consultation = Consultation::findOrFail($consultationId);
        $this->selectedConsultationId = $consultationId;
        
        // Pre-fill with minimum price based on consultation type
        if ($consultation->consultation_type === 'document_review') {
            $this->quotedPrice = $consultation->lawyer->lawyerProfile->document_review_min_price ?? '';
        } elseif ($consultation->consultation_type === 'video') {
            $this->quotedPrice = $consultation->lawyer->lawyerProfile->video_rate_per_15min ?? '';
        } elseif ($consultation->consultation_type === 'chat') {
            $this->quotedPrice = $consultation->lawyer->lawyerProfile->chat_rate_per_15min ?? '';
        } else {
            $this->quotedPrice = $consultation->rate ?? '';
        }
        
        $this->quoteNotes = '';
        $this->showQuoteModal = true;
    }

    public function acceptRequest(DeadlineCalculationService $deadlineService)
    {
        if (!$this->selectedConsultationId) {
            return;
        }

        $consultation = Consultation::findOrFail($this->selectedConsultationId);
        
        if ($consultation->lawyer_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            $this->closeModals();
            return;
        }

        // Check if lawyer can still accept (enough time remaining)
        $canAccept = $deadlineService->canLawyerAccept($consultation);
        if (!$canAccept['can_accept']) {
            session()->flash('error', $canAccept['reason']);
            $this->closeModals();
            return;
        }

        // Calculate payment deadline
        $consultation->accepted_at = now();
        $paymentDeadline = $deadlineService->calculatePaymentDeadline($consultation);

        $consultation->update([
            'status' => 'payment_pending',
            'accepted_at' => now(),
            'payment_deadline' => $paymentDeadline,
            'payment_deadline_calculated' => $paymentDeadline,
        ]);

        $consultation->client->notify(new \App\Notifications\ConsultationAccepted($consultation));

        session()->flash('success', 'Consultation request accepted! Client will be notified.');
        $this->closeModals();
    }

    public function declineRequest()
    {
        if (!$this->selectedConsultationId) {
            return;
        }

        $consultation = Consultation::findOrFail($this->selectedConsultationId);
        
        if ($consultation->lawyer_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            $this->closeModals();
            return;
        }

        $consultation->update([
            'status' => 'declined',
            'decline_reason' => $this->declineReason ?: 'Lawyer declined the request.',
        ]);

        $consultation->client->notify(new \App\Notifications\ConsultationDeclined($consultation));

        session()->flash('success', 'Consultation request declined.');
        $this->closeModals();
    }

    public function provideQuote(DeadlineCalculationService $deadlineService)
    {
        $this->validate([
            'quotedPrice' => 'required|numeric|min:1',
            'quoteNotes' => 'required|string|min:10|max:500',
        ]);

        if (!$this->selectedConsultationId) {
            return;
        }

        $consultation = Consultation::findOrFail($this->selectedConsultationId);
        
        if ($consultation->lawyer_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            $this->closeModals();
            return;
        }

        // Check if lawyer can still provide quote (enough time remaining)
        $canAccept = $deadlineService->canLawyerAccept($consultation);
        if (!$canAccept['can_accept']) {
            session()->flash('error', $canAccept['reason']);
            $this->closeModals();
            return;
        }

        // Calculate platform fee and total
        $platformFee = $this->quotedPrice * 0.10;
        $totalAmount = $this->quotedPrice + $platformFee;

        $consultation->update([
            'status' => 'awaiting_quote_approval',
            'quoted_price' => $this->quotedPrice,
            'quote_notes' => $this->quoteNotes,
            'quote_provided_at' => now(),
            'rate' => $this->quotedPrice,
            'platform_fee' => $platformFee,
            'total_amount' => $totalAmount,
        ]);

        // Calculate quote response deadline
        $consultation->quote_deadline = $deadlineService->calculateQuoteResponseDeadline($consultation);
        $consultation->save();

        // Send notification to client about quote
        $consultation->client->notify(new \App\Notifications\ConsultationQuoted($consultation));

        session()->flash('success', 'Quote sent to client successfully!');
        $this->closeModals();
    }
}
