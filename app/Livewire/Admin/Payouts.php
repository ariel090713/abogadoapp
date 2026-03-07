<?php

namespace App\Livewire\Admin;

use App\Models\Payout;
use App\Services\PayoutService;
use Livewire\Component;
use Livewire\WithPagination;

class Payouts extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $perPage = 15;

    // Create payout modal
    public $showCreateModal = false;
    public $selectedLawyers = [];
    public $batchNotes = '';

    // Process payout modal
    public $showProcessModal = false;
    public $processingPayout = null;
    public $payoutMethod = 'bank_transfer';
    public $referenceNumber = '';
    public $processNotes = '';

    // View details modal
    public $showDetailsModal = false;
    public $viewingPayout = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->selectedLawyers = [];
        $this->batchNotes = '';
    }

    public function toggleLawyer($lawyerId)
    {
        if (in_array($lawyerId, $this->selectedLawyers)) {
            $this->selectedLawyers = array_diff($this->selectedLawyers, [$lawyerId]);
        } else {
            $this->selectedLawyers[] = $lawyerId;
        }
    }

    public function selectAllLawyers()
    {
        $payoutService = app(PayoutService::class);
        $eligibleLawyers = $payoutService->getEligibleLawyers();
        $this->selectedLawyers = array_column($eligibleLawyers, 'lawyer');
        $this->selectedLawyers = array_map(fn($lawyer) => $lawyer->id, $this->selectedLawyers);
    }

    public function createBatchPayouts()
    {
        if (empty($this->selectedLawyers)) {
            session()->flash('error', 'Please select at least one lawyer.');
            return;
        }

        try {
            $payoutService = app(PayoutService::class);
            $results = $payoutService->createBatchPayouts(
                $this->selectedLawyers,
                auth()->id(),
                $this->batchNotes
            );

            $successCount = count($results['success']);
            $failedCount = count($results['failed']);

            if ($successCount > 0) {
                session()->flash('success', "Created {$successCount} payout(s) successfully.");
            }

            if ($failedCount > 0) {
                session()->flash('warning', "{$failedCount} payout(s) failed to create.");
            }

            $this->showCreateModal = false;
            $this->reset(['selectedLawyers', 'batchNotes']);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create payouts: ' . $e->getMessage());
        }
    }

    public function openProcessModal($payoutId)
    {
        $this->processingPayout = Payout::with(['lawyer', 'transactions'])->findOrFail($payoutId);
        $this->showProcessModal = true;
        $this->payoutMethod = 'bank_transfer';
        $this->referenceNumber = '';
        $this->processNotes = $this->processingPayout->notes ?? '';
    }

    public function markAsProcessing($payoutId)
    {
        try {
            $payout = Payout::findOrFail($payoutId);
            $payoutService = app(PayoutService::class);
            
            if ($payoutService->markAsProcessing($payout, auth()->id())) {
                session()->flash('success', 'Payout marked as processing.');
            } else {
                session()->flash('error', 'Cannot mark this payout as processing.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update payout: ' . $e->getMessage());
        }
    }

    public function completePayout()
    {
        $this->validate([
            'payoutMethod' => 'required|in:bank_transfer,gcash,paymaya,other',
            'referenceNumber' => 'required|string|min:5|max:100',
        ]);

        try {
            $payoutService = app(PayoutService::class);
            
            if ($payoutService->completePayout(
                $this->processingPayout,
                auth()->id(),
                $this->payoutMethod,
                $this->referenceNumber,
                $this->processNotes
            )) {
                session()->flash('success', 'Payout completed successfully. Lawyer has been notified.');
                $this->showProcessModal = false;
                $this->reset(['processingPayout', 'payoutMethod', 'referenceNumber', 'processNotes']);
            } else {
                session()->flash('error', 'Cannot complete this payout.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to complete payout: ' . $e->getMessage());
        }
    }

    public function markAsFailed($payoutId, $reason)
    {
        try {
            $payout = Payout::findOrFail($payoutId);
            $payoutService = app(PayoutService::class);
            
            if ($payoutService->markAsFailed($payout, auth()->id(), $reason)) {
                session()->flash('success', 'Payout marked as failed. Transactions have been unlinked.');
            } else {
                session()->flash('error', 'Cannot mark this payout as failed.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update payout: ' . $e->getMessage());
        }
    }

    public function viewDetails($payoutId)
    {
        $this->viewingPayout = Payout::with(['lawyer.lawyerProfile', 'transactions.consultation', 'transactions.documentRequest', 'processedBy'])
            ->findOrFail($payoutId);
        $this->showDetailsModal = true;
    }

    public function render()
    {
        $payoutService = app(PayoutService::class);

        // Get payouts
        $query = Payout::with(['lawyer', 'processedBy'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($this->search) {
            $query->whereHas('lawyer', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $payouts = $query->paginate($this->perPage);

        // Get stats
        $stats = $payoutService->getPayoutStats();

        // Get eligible lawyers for batch creation
        $eligibleLawyers = $payoutService->getEligibleLawyers();

        return view('livewire.admin.payouts', [
            'payouts' => $payouts,
            'stats' => $stats,
            'eligibleLawyers' => $eligibleLawyers,
        ]);
    }
}
