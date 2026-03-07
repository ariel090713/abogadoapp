<?php

namespace App\Livewire\Admin;

use App\Models\Consultation;
use Livewire\Component;
use Livewire\WithPagination;

class Consultations extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $dateFilter = 'all';

    protected $queryString = ['search', 'statusFilter', 'typeFilter', 'dateFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Consultation::with(['client', 'lawyer', 'transaction'])
            ->whereHas('client')
            ->latest();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('consultation_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('lawyer', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Type filter
        if ($this->typeFilter !== 'all') {
            $query->where('consultation_type', $this->typeFilter);
        }

        // Date filter
        if ($this->dateFilter !== 'all') {
            $query->where('created_at', '>=', match($this->dateFilter) {
                'today' => now()->startOfDay(),
                'week' => now()->startOfWeek(),
                'month' => now()->startOfMonth(),
                'year' => now()->startOfYear(),
            });
        }

        $consultations = $query->paginate(20);

        // Stats
        $stats = [
            'total' => Consultation::count(),
            'pending' => Consultation::where('status', 'pending')->count(),
            'active' => Consultation::whereIn('status', ['accepted', 'in_progress', 'scheduled'])->count(),
            'completed' => Consultation::where('status', 'completed')->count(),
            'cancelled' => Consultation::where('status', 'cancelled')->count(),
        ];

        return view('livewire.admin.consultations', [
            'consultations' => $consultations,
            'stats' => $stats,
        ])->layout('layouts.dashboard', ['title' => 'Consultations']);
    }
}
