<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $selectedUser = null;
    public $showUserModal = false;

    protected $queryString = ['search', 'roleFilter', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewUser($userId)
    {
        $this->selectedUser = User::with('lawyerProfile')->findOrFail($userId);
        $this->showUserModal = true;
    }

    public function closeModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
    }

    public function suspendUser($userId)
    {
        // Prevent admin from suspending themselves or other admins
        $user = User::findOrFail($userId);
        if ($user->role === 'admin') {
            session()->flash('error', 'Cannot suspend admin users.');
            return;
        }
        
        $user->update(['is_active' => false]);
        $this->showUserModal = false;
        session()->flash('success', 'User suspended successfully!');
    }

    public function activateUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => true]);
        $this->showUserModal = false;
        session()->flash('success', 'User activated successfully!');
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent admin from toggling themselves or other admins
        if ($user->role === 'admin') {
            session()->flash('error', 'Cannot modify admin user status.');
            return;
        }
        
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'suspended';
        session()->flash('success', "User {$status} successfully!");
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter))
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => User::count(),
            'clients' => User::where('role', 'client')->count(),
            'lawyers' => User::where('role', 'lawyer')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active' => User::where('is_active', true)->count(),
            'suspended' => User::where('is_active', false)->count(),
        ];

        return view('livewire.admin.user-management', [
            'users' => $users,
            'stats' => $stats,
        ])->layout('layouts.dashboard', ['title' => 'User Management']);
    }
}
