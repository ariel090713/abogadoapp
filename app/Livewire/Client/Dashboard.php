<?php

namespace App\Livewire\Client;

use App\Models\Consultation;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $upcomingConsultations = Consultation::forClient(auth()->id())
            ->upcoming()
            ->with(['lawyer.lawyerProfile'])
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        $recentConsultations = Consultation::forClient(auth()->id())
            ->completed()
            ->with(['lawyer.lawyerProfile'])
            ->orderBy('ended_at', 'desc')
            ->take(5)
            ->get();

        $pendingRequests = Consultation::forClient(auth()->id())
            ->pending()
            ->with(['lawyer.lawyerProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Document Drafting Requests
        $documentRequests = \App\Models\DocumentDraftingRequest::where('client_id', auth()->id())
            ->whereIn('status', ['pending_payment', 'paid', 'in_progress'])
            ->with(['lawyer', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total_consultations' => Consultation::forClient(auth()->id())->completed()->count(),
            'upcoming' => $upcomingConsultations->count(),
            'pending' => $pendingRequests->count(),
            'document_requests' => $documentRequests->count(),
        ];

        return view('livewire.client.dashboard', [
            'upcomingConsultations' => $upcomingConsultations,
            'recentConsultations' => $recentConsultations,
            'pendingRequests' => $pendingRequests,
            'documentRequests' => $documentRequests,
            'stats' => $stats,
        ])->layout('layouts.dashboard', ['title' => 'Dashboard']);
    }
}
