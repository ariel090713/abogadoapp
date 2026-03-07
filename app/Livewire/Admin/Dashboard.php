<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\LawyerProfile;
use App\Models\Consultation;
use App\Models\Transaction;
use App\Models\Payout;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_lawyers' => User::where('role', 'lawyer')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'pending_verifications' => LawyerProfile::whereHas('user')->where('is_verified', false)->count(),
            'total_consultations' => Consultation::count(),
            'active_consultations' => Consultation::whereIn('status', ['scheduled', 'in_progress'])->count(),
            'completed_consultations' => Consultation::where('status', 'completed')->count(),
            'pending_payouts' => Payout::where('status', 'pending')->count(),
            'total_revenue' => Transaction::where('status', 'captured')->sum('platform_fee'),
            'today_revenue' => Transaction::where('status', 'captured')->whereDate('processed_at', today())->sum('platform_fee'),
            'month_revenue' => Transaction::where('status', 'captured')->whereMonth('processed_at', now()->month)->sum('platform_fee'),
        ];

        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $pendingVerifications = LawyerProfile::where('is_verified', false)
            ->whereHas('user')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        $recentConsultations = Consultation::with(['client', 'lawyer'])
            ->latest()
            ->take(5)
            ->get();
        $pendingPayouts = Payout::where('status', 'pending')
            ->with('lawyer')
            ->latest()
            ->take(5)
            ->get();

        // User growth (last 7 days)
        $userGrowth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $userGrowth[] = [
                'date' => $date->format('M d'),
                'count' => User::whereDate('created_at', $date)->count(),
            ];
        }

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'pendingVerifications' => $pendingVerifications,
            'recentConsultations' => $recentConsultations,
            'pendingPayouts' => $pendingPayouts,
            'userGrowth' => $userGrowth,
        ])->layout('layouts.dashboard', ['title' => 'Admin Dashboard']);
    }

    public function verifyLawyer($lawyerId)
    {
        $lawyer = LawyerProfile::findOrFail($lawyerId);
        
        $lawyer->update([
            'is_verified' => true,
        ]);

        // Log admin action
        \App\Models\AdminAction::create([
            'admin_id' => auth()->id(),
            'action_type' => 'verify_lawyer',
            'target_type' => 'LawyerProfile',
            'target_id' => $lawyer->id,
            'notes' => 'Lawyer verified',
        ]);

        session()->flash('success', 'Lawyer verified successfully!');
    }

    public function approvePayout($payoutId)
    {
        $payout = Payout::findOrFail($payoutId);
        
        $payout->update([
            'status' => 'processing',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Log admin action
        \App\Models\AdminAction::create([
            'admin_id' => auth()->id(),
            'action_type' => 'process_payout',
            'target_type' => 'Payout',
            'target_id' => $payout->id,
            'notes' => 'Payout approved for processing',
        ]);

        session()->flash('success', 'Payout approved for processing!');
    }
}
