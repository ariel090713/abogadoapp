<?php

namespace App\Livewire\Admin;

use App\Exports\ClientActivityExport;
use App\Exports\ConsultationReportExport;
use App\Exports\LawyerPerformanceExport;
use App\Exports\PayoutReportExport;
use App\Exports\RefundReportExport;
use App\Exports\RevenueReportExport;
use App\Exports\TransactionReportExport;
use App\Services\ReportService;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends Component
{
    public $reportType = 'revenue';
    public $startDate;
    public $endDate;
    public $dateRange = 'this_month';

    // Report data
    public $revenueData = null;
    public $consultationData = null;
    public $lawyerPerformanceData = null;
    public $clientActivityData = null;
    public $transactionData = null;
    public $platformMetrics = null;
    public $refundData = null;
    public $payoutData = null;

    public function mount()
    {
        $this->setDateRange('this_month');
        $this->generateReport();
    }

    public function updatedDateRange($value)
    {
        $this->setDateRange($value);
        $this->generateReport();
    }

    public function updatedReportType()
    {
        $this->generateReport();
    }

    public function setDateRange($range)
    {
        switch ($range) {
            case 'today':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->startDate = now()->subDay()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->subDay()->endOfDay()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = now()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $this->startDate = now()->subWeek()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->subWeek()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_quarter':
                $this->startDate = now()->startOfQuarter()->format('Y-m-d');
                $this->endDate = now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'this_year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
            case 'last_year':
                $this->startDate = now()->subYear()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->subYear()->endOfYear()->format('Y-m-d');
                break;
            case 'all_time':
                $this->startDate = '2020-01-01';
                $this->endDate = now()->format('Y-m-d');
                break;
        }
    }

    public function generateReport()
    {
        $reportService = app(ReportService::class);

        try {
            switch ($this->reportType) {
                case 'revenue':
                    $this->revenueData = $reportService->getRevenueReport($this->startDate, $this->endDate);
                    break;
                case 'consultations':
                    $this->consultationData = $reportService->getConsultationReport($this->startDate, $this->endDate);
                    break;
                case 'lawyer_performance':
                    $this->lawyerPerformanceData = $reportService->getLawyerPerformanceReport($this->startDate, $this->endDate);
                    break;
                case 'client_activity':
                    $this->clientActivityData = $reportService->getClientActivityReport($this->startDate, $this->endDate);
                    break;
                case 'transactions':
                    $this->transactionData = $reportService->getTransactionReport($this->startDate, $this->endDate);
                    break;
                case 'platform_metrics':
                    $this->platformMetrics = $reportService->getPlatformMetrics($this->startDate, $this->endDate);
                    break;
                case 'refunds':
                    $this->refundData = $reportService->getRefundReport($this->startDate, $this->endDate);
                    break;
                case 'payouts':
                    $this->payoutData = $reportService->getPayoutReport($this->startDate, $this->endDate);
                    break;
            }

            session()->flash('success', 'Report generated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        try {
            $filename = $this->reportType . '_report_' . now()->format('Y-m-d_His') . '.xlsx';

            switch ($this->reportType) {
                case 'revenue':
                    return Excel::download(
                        new RevenueReportExport($this->revenueData, $this->startDate, $this->endDate),
                        $filename
                    );
                case 'consultations':
                    return Excel::download(
                        new ConsultationReportExport($this->consultationData),
                        $filename
                    );
                case 'lawyer_performance':
                    return Excel::download(
                        new LawyerPerformanceExport($this->lawyerPerformanceData),
                        $filename
                    );
                case 'client_activity':
                    return Excel::download(
                        new ClientActivityExport($this->clientActivityData),
                        $filename
                    );
                case 'transactions':
                    return Excel::download(
                        new TransactionReportExport($this->transactionData),
                        $filename
                    );
                case 'refunds':
                    return Excel::download(
                        new RefundReportExport($this->refundData),
                        $filename
                    );
                case 'payouts':
                    return Excel::download(
                        new PayoutReportExport($this->payoutData),
                        $filename
                    );
                default:
                    session()->flash('error', 'Excel export not available for this report type.');
                    return null;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to export report: ' . $e->getMessage());
            return null;
        }
    }

    public function exportCsv()
    {
        try {
            $filename = $this->reportType . '_report_' . now()->format('Y-m-d_His') . '.csv';

            switch ($this->reportType) {
                case 'revenue':
                    return Excel::download(
                        new RevenueReportExport($this->revenueData, $this->startDate, $this->endDate),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                case 'consultations':
                    return Excel::download(
                        new ConsultationReportExport($this->consultationData),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                case 'lawyer_performance':
                    return Excel::download(
                        new LawyerPerformanceExport($this->lawyerPerformanceData),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                case 'client_activity':
                    return Excel::download(
                        new ClientActivityExport($this->clientActivityData),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                case 'transactions':
                    return Excel::download(
                        new TransactionReportExport($this->transactionData),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                case 'refunds':
                    return Excel::download(
                        new RefundReportExport($this->refundData),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                case 'payouts':
                    return Excel::download(
                        new PayoutReportExport($this->payoutData),
                        $filename,
                        \Maatwebsite\Excel\Excel::CSV
                    );
                default:
                    session()->flash('error', 'CSV export not available for this report type.');
                    return null;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to export report: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.admin.reports');
    }
}
