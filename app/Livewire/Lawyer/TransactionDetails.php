<?php

namespace App\Livewire\Lawyer;

use App\Models\Transaction;
use App\Services\RefundService;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionDetails extends Component
{
    public Transaction $transaction;
    public bool $showRefundResponseModal = false;
    public string $refundResponse = 'approve';
    public string $lawyerNotes = '';

    public function mount($id)
    {
        $this->transaction = Transaction::with([
            'user',
            'lawyer',
            'consultation',
            'documentRequest',
            'refund'
        ])->findOrFail($id);

        // Verify lawyer owns this transaction
        if (auth()->id() !== $this->transaction->lawyer_id) {
            abort(403, 'Unauthorized');
        }
    }

    public function openRefundResponseModal()
    {
        $this->showRefundResponseModal = true;
        $this->refundResponse = 'approve';
        $this->lawyerNotes = '';
    }

    public function submitRefundResponse()
    {
        $this->validate([
            'refundResponse' => 'required|in:approve,reject',
            'lawyerNotes' => 'required_if:refundResponse,reject|nullable|string|min:10|max:500',
        ]);

        try {
            $refundService = app(RefundService::class);
            
            if ($this->refundResponse === 'approve') {
                $success = $refundService->lawyerApproveRefund(
                    $this->transaction->refund,
                    auth()->id(),
                    $this->lawyerNotes
                );
                $message = 'Refund request approved successfully.';
            } else {
                $success = $refundService->lawyerRejectRefund(
                    $this->transaction->refund,
                    auth()->id(),
                    $this->lawyerNotes
                );
                $message = 'Refund request rejected. Admin will review.';
            }

            if ($success) {
                session()->flash('success', $message);
                $this->showRefundResponseModal = false;
                $this->transaction->refresh();
            } else {
                session()->flash('error', 'Unable to process your response.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to submit refund response', [
                'transaction_id' => $this->transaction->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to submit response. Please try again.');
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
        return view('livewire.lawyer.transaction-details')
            ->layout('layouts.dashboard', ['title' => 'Transaction Details']);
    }
}
