<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Transactions extends Component
{
    use WithPagination;

    public $filterType = 'all'; // all, consultation_payment, document_drafting

    public function setFilter($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function render()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->with(['consultation', 'documentRequest'])
            ->latest();

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $transactions = $query->paginate(15);

        // Calculate spending summary
        $totalSpent = Transaction::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->sum('amount');

        return view('livewire.client.transactions', [
            'transactions' => $transactions,
            'totalSpent' => $totalSpent,
        ])->layout('layouts.dashboard');
    }
}
