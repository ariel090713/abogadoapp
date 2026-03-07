<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\DocumentRequest;
use App\Models\Payout;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get revenue report data
     */
    public function getRevenueReport(string $startDate, string $endDate): array
    {
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $dailyRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $byType = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        return [
            'total_revenue' => $transactions->sum('amount'),
            'total_transactions' => $transactions->count(),
            'platform_fee' => $transactions->sum('platform_fee'),
            'lawyer_payout' => $transactions->sum('lawyer_payout'),
            'average_transaction' => $transactions->avg('amount'),
            'daily_revenue' => $dailyRevenue,
            'by_type' => $byType,
        ];
    }

    /**
     * Get consultation report data
     */
    public function getConsultationReport(string $startDate, string $endDate): array
    {
        $consultations = Consultation::whereBetween('created_at', [$startDate, $endDate])->get();

        $byStatus = Consultation::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $byType = Consultation::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('consultation_type, COUNT(*) as count, AVG(total_amount) as avg_amount')
            ->groupBy('consultation_type')
            ->get();

        $completionRate = $consultations->where('status', 'completed')->count() / max($consultations->count(), 1) * 100;

        return [
            'total_consultations' => $consultations->count(),
            'completed' => $consultations->where('status', 'completed')->count(),
            'pending' => $consultations->where('status', 'pending')->count(),
            'cancelled' => $consultations->where('status', 'cancelled')->count(),
            'completion_rate' => round($completionRate, 2),
            'by_status' => $byStatus,
            'by_type' => $byType,
        ];
    }

    /**
     * Get lawyer performance report
     */
    public function getLawyerPerformanceReport(string $startDate, string $endDate): array
    {
        $lawyers = User::whereHas('lawyerProfile')
            ->with(['lawyerProfile', 'lawyerConsultations' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }, 'lawyerTransactions' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            }, 'receivedReviews' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get();

        $performanceData = $lawyers->map(function ($lawyer) {
            $consultations = $lawyer->lawyerConsultations;
            $transactions = $lawyer->lawyerTransactions;
            $reviews = $lawyer->receivedReviews;

            return [
                'lawyer_id' => $lawyer->id,
                'name' => $lawyer->name,
                'email' => $lawyer->email,
                'total_consultations' => $consultations->count(),
                'completed_consultations' => $consultations->where('status', 'completed')->count(),
                'total_revenue' => $transactions->sum('amount'),
                'total_earnings' => $transactions->sum('lawyer_payout'),
                'average_rating' => $reviews->avg('rating') ?? 0,
                'total_reviews' => $reviews->count(),
                'response_rate' => $this->calculateResponseRate($consultations),
            ];
        })->sortByDesc('total_revenue')->values();

        return [
            'lawyers' => $performanceData,
            'top_earners' => $performanceData->take(10),
            'top_rated' => $performanceData->sortByDesc('average_rating')->take(10),
        ];
    }

    /**
     * Get client activity report
     */
    public function getClientActivityReport(string $startDate, string $endDate): array
    {
        $clients = User::where('role', 'client')
            ->with(['clientConsultations' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }, 'transactions' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get();

        $activityData = $clients->map(function ($client) {
            $consultations = $client->clientConsultations;
            $transactions = $client->transactions;

            return [
                'client_id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'total_consultations' => $consultations->count(),
                'total_spent' => $transactions->where('status', 'completed')->sum('amount'),
                'last_activity' => $client->last_seen_at,
            ];
        })->sortByDesc('total_spent')->values();

        return [
            'total_clients' => $clients->count(),
            'active_clients' => $clients->where('last_seen_at', '>=', now()->subDays(30))->count(),
            'clients' => $activityData,
            'top_spenders' => $activityData->take(10),
        ];
    }

    /**
     * Get transaction report
     */
    public function getTransactionReport(string $startDate, string $endDate): array
    {
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'lawyer', 'consultation', 'documentRequest'])
            ->get();

        $byStatus = $transactions->groupBy('status')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        });

        $byPaymentMethod = $transactions->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        });

        return [
            'total_transactions' => $transactions->count(),
            'total_amount' => $transactions->sum('amount'),
            'completed_transactions' => $transactions->where('status', 'completed')->count(),
            'completed_amount' => $transactions->where('status', 'completed')->sum('amount'),
            'pending_transactions' => $transactions->where('status', 'pending')->count(),
            'pending_amount' => $transactions->where('status', 'pending')->sum('amount'),
            'refunded_transactions' => $transactions->where('status', 'refunded')->count(),
            'refunded_amount' => $transactions->where('status', 'refunded')->sum('amount'),
            'by_status' => $byStatus,
            'by_payment_method' => $byPaymentMethod,
            'transactions' => $transactions,
        ];
    }

    /**
     * Get platform metrics
     */
    public function getPlatformMetrics(string $startDate, string $endDate): array
    {
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $newLawyers = User::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('lawyerProfile')
            ->count();
        $newClients = User::whereBetween('created_at', [$startDate, $endDate])
            ->where('role', 'client')
            ->count();

        $consultations = Consultation::whereBetween('created_at', [$startDate, $endDate])->count();
        $documentRequests = DocumentRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $revenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        $refunds = Refund::whereBetween('created_at', [$startDate, $endDate])->count();
        $refundAmount = Refund::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('refund_amount');

        $payouts = Payout::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'new_users' => $newUsers,
            'new_lawyers' => $newLawyers,
            'new_clients' => $newClients,
            'total_consultations' => $consultations,
            'total_document_requests' => $documentRequests,
            'total_revenue' => $revenue,
            'total_refunds' => $refunds,
            'refund_amount' => $refundAmount,
            'total_payouts' => $payouts,
            'net_revenue' => $revenue - $refundAmount,
        ];
    }

    /**
     * Get refund report
     */
    public function getRefundReport(string $startDate, string $endDate): array
    {
        $refunds = Refund::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'lawyer', 'transaction'])
            ->get();

        $byStatus = $refunds->groupBy('status')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('refund_amount'),
            ];
        });

        $byReason = $refunds->groupBy('reason')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('refund_amount'),
            ];
        });

        return [
            'total_refunds' => $refunds->count(),
            'total_amount' => $refunds->sum('refund_amount'),
            'approved_refunds' => $refunds->where('status', 'approved')->count(),
            'completed_refunds' => $refunds->where('status', 'completed')->count(),
            'pending_refunds' => $refunds->where('status', 'pending')->count(),
            'by_status' => $byStatus,
            'by_reason' => $byReason,
            'refunds' => $refunds,
        ];
    }

    /**
     * Get payout report
     */
    public function getPayoutReport(string $startDate, string $endDate): array
    {
        $payouts = Payout::whereBetween('created_at', [$startDate, $endDate])
            ->with(['lawyer', 'transactions'])
            ->get();

        $byStatus = $payouts->groupBy('status')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        });

        return [
            'total_payouts' => $payouts->count(),
            'total_amount' => $payouts->sum('amount'),
            'completed_payouts' => $payouts->where('status', 'completed')->count(),
            'completed_amount' => $payouts->where('status', 'completed')->sum('amount'),
            'pending_payouts' => $payouts->where('status', 'pending')->count(),
            'pending_amount' => $payouts->where('status', 'pending')->sum('amount'),
            'by_status' => $byStatus,
            'payouts' => $payouts,
        ];
    }

    /**
     * Calculate response rate for consultations
     */
    private function calculateResponseRate($consultations): float
    {
        if ($consultations->isEmpty()) {
            return 0;
        }

        $responded = $consultations->whereIn('status', ['accepted', 'quoted', 'completed'])->count();
        return round(($responded / $consultations->count()) * 100, 2);
    }
}
