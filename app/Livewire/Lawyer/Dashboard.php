<?php

namespace App\Livewire\Lawyer;

use App\Models\Consultation;
use App\Models\Transaction;
use Livewire\Component;

class Dashboard extends Component
{
    // Modal state
    public $showAcceptModal = false;
    public $showDeclineModal = false;
    public $selectedConsultationId = null;
    public $declineReason = '';

    public function render()
    {
        $lawyer = auth()->user()->lawyerProfile;
        
        // If no lawyer profile, redirect to onboarding
        if (!$lawyer) {
            return redirect()->route('onboarding.start');
        }

        $todayConsultations = Consultation::forLawyer(auth()->id())
            ->whereDate('scheduled_at', today())
            ->with(['client'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $pendingRequests = Consultation::forLawyer(auth()->id())
            ->pending()
            ->with(['client'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingConsultations = Consultation::forLawyer(auth()->id())
            ->upcoming()
            ->with(['client'])
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        // Document Drafting Requests
        $pendingDocumentRequests = \App\Models\DocumentDraftingRequest::where('lawyer_id', auth()->id())
            ->whereIn('status', ['pending_payment', 'paid', 'in_progress'])
            ->with(['client', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Earnings calculations
        $todayEarnings = Transaction::whereHas('consultation', function($query) {
                $query->where('lawyer_id', auth()->id());
            })
            ->whereDate('processed_at', today())
            ->where('status', 'captured')
            ->sum('lawyer_payout');

        $weekEarnings = Transaction::whereHas('consultation', function($query) {
                $query->where('lawyer_id', auth()->id());
            })
            ->whereBetween('processed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'captured')
            ->sum('lawyer_payout');

        $monthEarnings = Transaction::whereHas('consultation', function($query) {
                $query->where('lawyer_id', auth()->id());
            })
            ->whereMonth('processed_at', now()->month)
            ->where('status', 'captured')
            ->sum('lawyer_payout');

        $stats = [
            'pending_requests' => $pendingRequests->count(),
            'today_consultations' => $todayConsultations->count(),
            'total_clients' => Consultation::forLawyer(auth()->id())->completed()->distinct('client_id')->count('client_id'),
            'pending_documents' => $pendingDocumentRequests->count(),
            'rating' => $lawyer->rating ?? 0,
            'today_earnings' => $todayEarnings,
            'week_earnings' => $weekEarnings,
            'month_earnings' => $monthEarnings,
        ];

        return view('livewire.lawyer.dashboard', [
            'todayConsultations' => $todayConsultations,
            'pendingRequests' => $pendingRequests,
            'upcomingConsultations' => $upcomingConsultations,
            'pendingDocumentRequests' => $pendingDocumentRequests,
            'stats' => $stats,
            'lawyer' => $lawyer,
        ])->layout('layouts.dashboard', ['title' => 'Lawyer Dashboard']);
    }

    public function openAcceptModal($consultationId)
    {
        $this->selectedConsultationId = $consultationId;
        $this->showAcceptModal = true;
    }

    public function openDeclineModal($consultationId)
    {
        $this->selectedConsultationId = $consultationId;
        $this->showDeclineModal = true;
        $this->declineReason = '';
    }

    public function closeModals()
    {
        $this->showAcceptModal = false;
        $this->showDeclineModal = false;
        $this->selectedConsultationId = null;
        $this->declineReason = '';
    }

    public function acceptRequest()
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
            'status' => 'payment_pending',
            'accepted_at' => now(),
            'payment_deadline' => now()->addMinutes(30),
        ]);

        // Send notification to client
        $consultation->client->notify(new \App\Notifications\ConsultationAccepted($consultation));

        session()->flash('success', 'Consultation request accepted! Client has 30 minutes to complete payment.');
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

        // Send notification to client
        $consultation->client->notify(new \App\Notifications\ConsultationDeclined($consultation));

        session()->flash('success', 'Consultation request declined.');
        $this->closeModals();
    }
}
