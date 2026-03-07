<?php

namespace App\Livewire\Admin;

use App\Models\Refund;
use App\Services\RefundService;
use Livewire\Component;
use Livewire\WithPagination;

class RefundManagement extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    
    // Modal states
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showDetailsModal = false;
    public $showProcessModal = false;
    public $selectedRefundId = null;
    public $selectedRefund = null;
    public $adminNotes = '';
    public $rejectionReason = '';

    protected $queryString = ['statusFilter', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function selectRefundForApproval($refundId)
    {
        $this->selectedRefundId = $refundId;
        $this->showApproveModal = true;
        $this->adminNotes = '';
    }

    public function selectRefundForRejection($refundId)
    {
        $this->selectedRefundId = $refundId;
        $this->showRejectModal = true;
        $this->rejectionReason = '';
    }

    public function viewRefundDetails($refundId)
    {
        $this->selectedRefund = Refund::with(['user', 'transaction', 'consultation', 'documentRequest', 'approvedBy', 'lawyer'])
            ->findOrFail($refundId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedRefund = null;
    }

    public function approveRefund()
    {
        $this->validate([
            'adminNotes' => 'nullable|string|max:500',
        ]);

        try {
            $refund = Refund::findOrFail($this->selectedRefundId);
            $refundService = app(RefundService::class);
            
            $success = $refundService->approveRefund($refund, auth()->id(), $this->adminNotes);
            
            if ($success) {
                session()->flash('success', 'Refund approved successfully.');
            } else {
                session()->flash('error', 'Unable to approve refund. It may have already been processed.');
            }
            
            $this->showApproveModal = false;
            $this->reset(['selectedRefundId', 'adminNotes']);
            
        } catch (\Exception $e) {
            \Log::error('Failed to approve refund', [
                'refund_id' => $this->selectedRefundId,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to approve refund. Please try again.');
        }
    }

    public function rejectRefund()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ]);

        try {
            $refund = Refund::findOrFail($this->selectedRefundId);
            $refundService = app(RefundService::class);
            
            $success = $refundService->rejectRefund($refund, auth()->id(), $this->rejectionReason);
            
            if ($success) {
                session()->flash('success', 'Refund rejected.');
            } else {
                session()->flash('error', 'Unable to reject refund. It may have already been processed.');
            }
            
            $this->showRejectModal = false;
            $this->reset(['selectedRefundId', 'rejectionReason']);
            
        } catch (\Exception $e) {
            \Log::error('Failed to reject refund', [
                'refund_id' => $this->selectedRefundId,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to reject refund. Please try again.');
        }
    }

    public function selectRefundForProcessing($refundId)
    {
        $this->selectedRefundId = $refundId;
        $this->selectedRefund = Refund::with(['user', 'transaction'])->findOrFail($refundId);
        $this->showProcessModal = true;
    }

    public function closeProcessModal()
    {
        $this->showProcessModal = false;
        $this->selectedRefundId = null;
        $this->selectedRefund = null;
    }

    public function confirmProcessRefund()
    {
        try {
            $refund = Refund::with('transaction')->findOrFail($this->selectedRefundId);
            
            // Check if transaction has payment_intent_id (this is what we actually use for refunds)
            if (!$refund->transaction->paymongo_payment_intent_id) {
                session()->flash('error', 'This transaction cannot be refunded. Missing PayMongo payment intent ID.');
                $this->closeProcessModal();
                return;
            }
            
            $refundService = app(RefundService::class);
            
            // Reset status to approved if it was failed (for retry)
            if ($refund->status === 'failed') {
                $refund->update(['status' => 'approved']);
            }
            
            $success = $refundService->processRefund($refund);
            
            if ($success) {
                session()->flash('success', 'Refund processing initiated. Waiting for PayMongo confirmation via webhook.');
                $this->closeProcessModal();
            } else {
                session()->flash('error', 'Unable to process refund. Check logs for details.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to process refund', [
                'refund_id' => $this->selectedRefundId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    public function processRefund($refundId)
    {
        // Deprecated - use selectRefundForProcessing instead
        $this->selectRefundForProcessing($refundId);
    }

    public function render()
    {
        $query = Refund::with(['user', 'transaction', 'consultation', 'documentRequest'])
            ->orderBy('created_at', 'desc');

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('transaction', function($txQuery) {
                    $txQuery->where('reference_number', 'like', '%' . $this->search . '%');
                });
            });
        }

        $refunds = $query->paginate(15);

        // Get stats
        $stats = [
            'pending' => Refund::where('status', 'pending')->count(),
            'approved' => Refund::where('status', 'approved')->count(),
            'completed' => Refund::where('status', 'completed')->count(),
            'rejected' => Refund::where('status', 'rejected')->count(),
            'total_amount' => Refund::whereIn('status', ['approved', 'completed'])->sum('refund_amount'),
        ];

        return view('livewire.admin.refund-management', [
            'refunds' => $refunds,
            'stats' => $stats,
        ])->layout('layouts.dashboard', ['title' => 'Refund Management']);
    }
}
