<?php

namespace App\Livewire\Admin;

use App\Models\LawyerProfile;
use App\Models\AdminAction;
use Livewire\Component;
use Livewire\WithPagination;

class LawyerVerification extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending'; // pending, verified, all
    public $selectedLawyer = null;
    public $showDetailsModal = false;
    public $showRejectModal = false;
    public $rejectReason = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewDetails($lawyerId)
    {
        $this->selectedLawyer = LawyerProfile::with(['user', 'specializations'])->findOrFail($lawyerId);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->showRejectModal = false;
        $this->selectedLawyer = null;
        $this->rejectReason = '';
    }

    public function openRejectModal($lawyerId)
    {
        $this->selectedLawyer = LawyerProfile::with('user')->findOrFail($lawyerId);
        $this->showRejectModal = true;
    }

    public function verifyLawyer($lawyerId)
    {
        $lawyer = LawyerProfile::findOrFail($lawyerId);
        
        $lawyer->update([
            'is_verified' => true,
            'verified_at' => now(),
            'is_rejected' => false,
            'rejection_reason' => null,
            'rejected_at' => null,
        ]);

        // Log admin action
        AdminAction::create([
            'admin_id' => auth()->id(),
            'action_type' => 'verify_lawyer',
            'target_type' => 'LawyerProfile',
            'target_id' => $lawyer->id,
            'notes' => 'Lawyer verified',
        ]);

        // Send notification to lawyer
        $lawyer->user->notify(new \App\Notifications\LawyerVerified($lawyer));

        $this->closeModal();
        session()->flash('success', 'Lawyer verified successfully!');
    }

    public function toggleVerification($lawyerId)
    {
        $lawyer = LawyerProfile::findOrFail($lawyerId);
        
        $newStatus = !$lawyer->is_verified;
        
        $lawyer->update([
            'is_verified' => $newStatus,
            'verified_at' => $newStatus ? now() : null,
        ]);

        // Log admin action
        AdminAction::create([
            'admin_id' => auth()->id(),
            'action_type' => $newStatus ? 'verify_lawyer' : 'unverify_lawyer',
            'target_type' => 'LawyerProfile',
            'target_id' => $lawyer->id,
            'notes' => $newStatus ? 'Lawyer verified' : 'Lawyer unverified',
        ]);

        session()->flash('success', $newStatus ? 'Lawyer verified!' : 'Lawyer unverified!');
    }

    public function rejectLawyer()
    {
        $this->validate([
            'rejectReason' => 'required|min:10',
        ]);

        if (!$this->selectedLawyer) {
            return;
        }

        $this->selectedLawyer->update([
            'is_rejected' => true,
            'rejection_reason' => $this->rejectReason,
            'rejected_at' => now(),
            'is_verified' => false,
            'verified_at' => null,
        ]);

        // Log admin action
        AdminAction::create([
            'admin_id' => auth()->id(),
            'action_type' => 'reject_lawyer',
            'target_type' => 'LawyerProfile',
            'target_id' => $this->selectedLawyer->id,
            'notes' => 'Lawyer rejected: ' . $this->rejectReason,
        ]);

        // Send notification to lawyer
        $this->selectedLawyer->user->notify(new \App\Notifications\LawyerRejected($this->selectedLawyer, $this->rejectReason));

        $this->closeModal();
        session()->flash('success', 'Lawyer application rejected.');
    }

    public function render()
    {
        $query = LawyerProfile::whereHas('user')->with(['user', 'specializations']);

        // Search
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })->orWhere('ibp_number', 'like', "%{$this->search}%");
        }

        // Status filter
        if ($this->statusFilter === 'pending') {
            $query->where('is_verified', false)->where('is_rejected', false);
        } elseif ($this->statusFilter === 'verified') {
            $query->where('is_verified', true);
        } elseif ($this->statusFilter === 'rejected') {
            $query->where('is_rejected', true);
        }

        $lawyers = $query->latest()->paginate(20);

        $stats = [
            'total' => LawyerProfile::whereHas('user')->count(),
            'pending' => LawyerProfile::whereHas('user')->where('is_verified', false)->where('is_rejected', false)->count(),
            'verified' => LawyerProfile::whereHas('user')->where('is_verified', true)->count(),
            'rejected' => LawyerProfile::whereHas('user')->where('is_rejected', true)->count(),
        ];

        return view('livewire.admin.lawyer-verification', [
            'lawyers' => $lawyers,
            'stats' => $stats,
        ])->layout('layouts.dashboard', ['title' => 'Lawyer Verification']);
    }
}
