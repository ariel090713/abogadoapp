<?php

namespace App\Livewire\Client;

use App\Models\Consultation;
use Livewire\Component;
use Livewire\WithPagination;

class Consultations extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url]
    public $filter = 'pending'; // pending, payment_pending, scheduled, in_progress, completed, declined, cancelled, all
    public $search = ''; // Search query
    public $typeFilter = ''; // Filter by consultation type
    
    // Modal state
    public $showCancelModal = false;
    public $showQuoteModal = false;
    public $selectedConsultationId = null;
    public $cancelReason = '';
    public $selectedConsultation = null;

    public function render()
    {
        $query = Consultation::forClient(auth()->id())
            ->with(['lawyer.lawyerProfile'])
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
            $query->where('status', 'payment_pending');
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
                  ->orWhereHas('lawyer', function($lawyerQuery) {
                      $lawyerQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply type filter
        if ($this->typeFilter) {
            $query->where('consultation_type', $this->typeFilter);
        }

        $consultations = $query->paginate(12);

        $counts = [
            'all' => Consultation::forClient(auth()->id())->count(),
            'pending' => Consultation::forClient(auth()->id())->pending()->count(),
            'payment_pending' => Consultation::forClient(auth()->id())->where('status', 'payment_pending')->count(),
            'scheduled' => Consultation::forClient(auth()->id())->where('status', 'scheduled')->count(),
            'in_progress' => Consultation::forClient(auth()->id())->where('status', 'in_progress')->count(),
            'completed' => Consultation::forClient(auth()->id())->completed()->count(),
            'cancelled' => Consultation::forClient(auth()->id())->whereIn('status', ['cancelled', 'declined', 'expired', 'payment_failed'])->count(),
            'awaiting_quote' => Consultation::forClient(auth()->id())->where('status', 'awaiting_quote_approval')->count(),
        ];

        return view('livewire.client.consultations', [
            'consultations' => $consultations,
            'counts' => $counts,
        ])->layout('layouts.dashboard', ['title' => 'My Consultations']);
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

    public function openCancelModal($consultationId)
    {
        $this->selectedConsultationId = $consultationId;
        $this->showCancelModal = true;
        $this->cancelReason = '';
    }

    public function closeModal()
    {
        $this->showCancelModal = false;
        $this->showQuoteModal = false;
        $this->selectedConsultationId = null;
        $this->selectedConsultation = null;
        $this->cancelReason = '';
    }

    public function openQuoteModal($consultationId)
    {
        $this->selectedConsultationId = $consultationId;
        $this->selectedConsultation = Consultation::with('lawyer.lawyerProfile')->findOrFail($consultationId);
        $this->showQuoteModal = true;
    }

    public function cancelConsultation()
    {
        if (!$this->selectedConsultationId) {
            return;
        }

        $consultation = Consultation::findOrFail($this->selectedConsultationId);
        
        if ($consultation->client_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            $this->closeModal();
            return;
        }

        if (!in_array($consultation->status, ['pending', 'payment_pending', 'scheduled', 'awaiting_quote_approval'])) {
            session()->flash('error', 'Cannot cancel this consultation.');
            $this->closeModal();
            return;
        }

        $consultation->update([
            'status' => 'cancelled',
            'cancel_reason' => $this->cancelReason ?: 'Cancelled by client',
        ]);

        session()->flash('success', 'Consultation cancelled successfully.');
        $this->closeModal();
    }

    public function acceptQuote()
    {
        if (!$this->selectedConsultationId) {
            return;
        }

        $consultation = Consultation::findOrFail($this->selectedConsultationId);
        
        if ($consultation->client_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            $this->closeModal();
            return;
        }

        if ($consultation->status !== 'awaiting_quote_approval') {
            session()->flash('error', 'Invalid consultation status.');
            $this->closeModal();
            return;
        }

        // Inject deadline service
        $deadlineService = app(\App\Services\DeadlineCalculationService::class);

        // Validate if client can still pay (enough time before session)
        $canPayCheck = $deadlineService->canClientPay($consultation);
        if (!$canPayCheck['can_pay']) {
            session()->flash('error', $canPayCheck['reason']);
            $this->closeModal();
            return;
        }

        // Calculate payment deadline
        $paymentDeadline = $deadlineService->calculatePaymentDeadline($consultation);

        $consultation->update([
            'status' => 'payment_pending',
            'quote_accepted_at' => now(),
            'accepted_at' => now(),
            'payment_deadline' => $paymentDeadline,
            'payment_deadline_calculated' => $paymentDeadline,
        ]);

        // Send notification to lawyer about quote acceptance
        $consultation->lawyer->notify(new \App\Notifications\QuoteAccepted($consultation));

        // Get time remaining for flash message
        $timeRemaining = $deadlineService->getTimeRemaining($paymentDeadline);
        
        session()->flash('success', sprintf(
            'Quote accepted! Please complete payment within %s.',
            $timeRemaining['formatted']
        ));
        $this->closeModal();
        
        return redirect()->route('payment.checkout', $consultation);
    }

    public function declineQuote()
    {
        if (!$this->selectedConsultationId) {
            return;
        }

        $consultation = Consultation::findOrFail($this->selectedConsultationId);
        
        if ($consultation->client_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            $this->closeModal();
            return;
        }

        if ($consultation->status !== 'awaiting_quote_approval') {
            session()->flash('error', 'Invalid consultation status.');
            $this->closeModal();
            return;
        }

        $consultation->update([
            'status' => 'declined',
            'decline_reason' => 'Client declined the quote',
        ]);

        // Send notification to lawyer about quote decline
        $consultation->lawyer->notify(new \App\Notifications\QuoteDeclined($consultation));

        session()->flash('success', 'Quote declined. You can search for other lawyers.');
        $this->closeModal();
    }
}
