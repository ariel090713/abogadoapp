<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="mt-2 text-gray-600">Comprehensive reports and insights for platform performance</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Report Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select wire:model.live="reportType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="revenue">Revenue Report</option>
                    <option value="consultations">Consultation Report</option>
                    <option value="lawyer_performance">Lawyer Performance</option>
                    <option value="client_activity">Client Activity</option>
                    <option value="transactions">Transaction Report</option>
                    <option value="platform_metrics">Platform Metrics</option>
                    <option value="refunds">Refund Report</option>
                    <option value="payouts">Payout Report</option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select wire:model.live="dateRange" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="this_week">This Week</option>
                    <option value="last_week">Last Week</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_quarter">This Quarter</option>
                    <option value="this_year">This Year</option>
                    <option value="last_year">Last Year</option>
                    <option value="all_time">All Time</option>
                </select>
            </div>

            <!-- Export Buttons -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Export</label>
                <div class="flex gap-2">
                    <button wire:click="exportExcel" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Excel
                    </button>
                    <button wire:click="exportCsv" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        CSV
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-600">
            Showing data from <span class="font-semibold">{{ $startDate }}</span> to <span class="font-semibold">{{ $endDate }}</span>
        </div>
    </div>

    <!-- Report Content -->
    <div id="report-content">
        @include('livewire.admin.reports.' . str_replace('_', '-', $reportType))
    </div>
</div>
