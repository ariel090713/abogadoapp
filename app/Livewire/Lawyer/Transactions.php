<?php

namespace App\Livewire\Lawyer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Transactions extends Component
{
    use WithPagination;

    public $activeTab = 'transactions'; // transactions or subscription
    public $filterType = 'all'; // all, consultation_payment, document_drafting

    public function mount()
    {
        // Check if there's a tab parameter in the URL
        if (request()->has('tab')) {
            $this->activeTab = request()->get('tab');
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function setFilter($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function render()
    {
        $query = Transaction::where('lawyer_id', auth()->id())
            ->with(['user', 'consultation', 'documentRequest'])
            ->latest();

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $transactions = $query->paginate(15);

        // Calculate earnings summary
        $totalEarnings = Transaction::where('lawyer_id', auth()->id())
            ->whereIn('status', ['completed', 'captured'])
            ->sum('lawyer_payout');

        $pendingEarnings = Transaction::where('lawyer_id', auth()->id())
            ->where('status', 'pending')
            ->sum('lawyer_payout');

        return view('livewire.lawyer.transactions', [
            'transactions' => $transactions,
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings,
        ])->layout('layouts.dashboard');
    }
}
