<?php

namespace App\Livewire\Client;

use App\Models\Transaction;
use App\Services\RefundService;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionDetails extends Component
{
    public Transaction $transaction;
    public bool $showRefundModal = false;
    public string $refundReason = '';
    public string $refundDetails = '';

    public function mount($id)
    {
        $this->transaction = Transaction::with([
            'user',
            'lawyer',
            'consultation',
            'documentRequest',
            'refund'
        ])->findOrFail($id);

        // Verify user owns this transaction
        if (auth()->id() !== $this->transaction->user_id) {
            abort(403, 'Unauthorized');
        }
    }

    public function getCanRequestRefundProperty()
    {
        $refundService = app(RefundService::class);
        return $refundService->isEligibleForRefund($this->transaction);
    }

    public function submitRefundRequest()
    {
        $this->validate([
            'refundReason' => 'required|in:document_not_delivered,dispute,other',
            'refundDetails' => 'required|string|min:20|max:1000',
        ]);

        try {
            $refundService = app(RefundService::class);
            
            $refundService->createManualRefund(
                $this->transaction,
                $this->refundReason,
                $this->refundDetails,
                'full' // Manual refunds default to full amount, admin can adjust
            );

            session()->flash('success', 'Refund request submitted successfully. Our team will review it shortly.');
            
            $this->showRefundModal = false;
            $this->reset(['refundReason', 'refundDetails']);
            
            // Refresh transaction to show refund
            $this->transaction->refresh();
            
        } catch (\Exception $e) {
            \Log::error('Failed to submit refund request', [
                'transaction_id' => $this->transaction->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to submit refund request. Please try again.');
        }
    }

    public function downloadInvoice()
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'transaction' => $this->transaction
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'invoice-' . $this->transaction->reference_number . '.pdf');
    }

    public function downloadReceipt()
    {
        $pdf = Pdf::loadView('pdf.receipt', [
            'transaction' => $this->transaction
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'receipt-' . $this->transaction->reference_number . '.pdf');
    }

    public function render()
    {
        return view('livewire.client.transaction-details')
            ->layout('layouts.dashboard', ['title' => 'Transaction Details']);
    }
}
