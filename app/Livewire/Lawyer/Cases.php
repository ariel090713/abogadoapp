<?php

namespace App\Livewire\Lawyer;

use App\Models\Consultation;
use Livewire\Component;
use Livewire\WithPagination;

class Cases extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';

    public function render()
    {
        // Get all main consultations (cases) for this lawyer
        $query = Consultation::where('lawyer_id', auth()->id())
            ->whereNull('parent_consultation_id') // Only main cases
            ->with(['client', 'childConsultations'])
            ->latest();

        // Apply search filter
        if ($this->search) {
            $query->whereHas('client', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'active') {
                $query->whereIn('status', ['pending', 'payment_pending', 'scheduled', 'in_progress', 'awaiting_quote_approval']);
            } elseif ($this->statusFilter === 'completed') {
                $query->where('status', 'completed');
            } elseif ($this->statusFilter === 'cancelled') {
                $query->where('status', 'cancelled');
            }
        }

        $cases = $query->paginate(10);

        return view('livewire.lawyer.cases', [
            'cases' => $cases,
        ])->layout('layouts.dashboard', ['title' => 'Consultation Threads']);
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
