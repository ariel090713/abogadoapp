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
            
            // Additional stats
            'pending_refunds' => \App\Models\Refund::where('status', 'pending')->count(),
            'total_reviews' => \App\Models\Review::count(),
            'avg_rating' => \App\Models\Review::avg('rating') ?? 0,
            'active_sessions' => Consultation::where('status', 'in_progress')->count(),
            'cancelled_consultations' => Consultation::where('status', 'cancelled')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
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

        // Revenue trend (last 7 days)
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenueTrend[] = [
                'date' => $date->format('M d'),
                'amount' => Transaction::where('status', 'captured')
                    ->whereDate('processed_at', $date)
                    ->sum('platform_fee'),
            ];
        }

        // Activity timeline (last 10 activities)
        $activities = collect();
        
        // Recent users
        User::latest()->take(3)->get()->each(function($user) use ($activities) {
            $activities->push([
                'type' => 'user_registered',
                'icon' => 'user-plus',
                'color' => 'blue',
                'title' => 'New User Registered',
                'description' => $user->name . ' joined as ' . $user->role,
                'time' => $user->created_at,
            ]);
        });

        // Recent consultations
        Consultation::latest()->take(3)->get()->each(function($consultation) use ($activities) {
            $activities->push([
                'type' => 'consultation',
                'icon' => 'calendar',
                'color' => 'green',
                'title' => 'New Consultation',
                'description' => ucfirst($consultation->consultation_type) . ' - ' . ucfirst($consultation->status),
                'time' => $consultation->created_at,
            ]);
        });

        // Recent transactions
        Transaction::where('status', 'captured')->latest()->take(3)->get()->each(function($transaction) use ($activities) {
            $activities->push([
                'type' => 'payment',
                'icon' => 'currency',
                'color' => 'accent',
                'title' => 'Payment Received',
                'description' => '₱' . number_format($transaction->amount, 2) . ' via ' . ucfirst($transaction->payment_method),
                'time' => $transaction->processed_at,
            ]);
        });

        $activities = $activities->sortByDesc('time')->take(10)->values();

        // System health
        $systemHealth = [
            'database' => 'healthy',
            'storage' => 'healthy',
            'queue' => 'healthy',
        ];

        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            $systemHealth['database'] = 'error';
        }

        // Consultation status breakdown
        $consultationBreakdown = [
            'pending' => Consultation::where('status', 'pending')->count(),
            'scheduled' => Consultation::where('status', 'scheduled')->count(),
            'in_progress' => Consultation::where('status', 'in_progress')->count(),
            'completed' => Consultation::where('status', 'completed')->count(),
            'cancelled' => Consultation::where('status', 'cancelled')->count(),
        ];

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'pendingVerifications' => $pendingVerifications,
            'recentConsultations' => $recentConsultations,
            'pendingPayouts' => $pendingPayouts,
            'userGrowth' => $userGrowth,
            'revenueTrend' => $revenueTrend,
            'activities' => $activities,
            'systemHealth' => $systemHealth,
            'consultationBreakdown' => $consultationBreakdown,
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
