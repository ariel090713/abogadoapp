<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'typeFilter' => ['except' => 'all'],
    ];

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

    public function render()
    {
        $query = Transaction::with(['user', 'lawyer', 'consultation', 'documentRequest', 'refund', 'payout'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('lawyer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('paymongo_payment_intent_id', 'like', '%' . $this->search . '%')
                ->orWhere('paymongo_payment_id', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Type filter
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        $transactions = $query->paginate($this->perPage);

        // Stats
        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'refunded' => Transaction::where('status', 'refunded')->count(),
            'total_amount' => Transaction::where('status', 'completed')->sum('amount'),
            'in_hold' => Transaction::where('status', 'completed')
                ->whereNull('payout_id')
                ->whereNull('refund_id')
                ->where('created_at', '>', now()->subDays(7))
                ->count(),
            'eligible_for_payout' => Transaction::where('status', 'completed')
                ->whereNull('payout_id')
                ->whereNull('refund_id')
                ->where('created_at', '<=', now()->subDays(7))
                ->count(),
        ];

        return view('livewire.admin.transactions', [
            'transactions' => $transactions,
            'stats' => $stats,
        ]);
    }
}
