<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="reportsData()">
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="mt-2 text-gray-600">Comprehensive reports and insights for platform performance</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select wire:model.live="reportType" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="revenue">Revenue Report</option>
                    <option value="consultations">Consultation Report</option>
                    <option value="lawyer_performance">Lawyer Performance</option>
                    <option value="platform_metrics">Platform Metrics</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select wire:model.live="dateRange" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_year">This Year</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Export</label>
                <div class="flex gap-2">
                    <button wire:click="exportExcel" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Excel</button>
                    <button wire:click="exportCsv" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">CSV</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Report -->
    @if($reportType === 'revenue' && $revenueData)
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Revenue</div>
                    <div class="text-3xl font-bold text-primary-700">₱{{ number_format($revenueData['total_revenue'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Transactions</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($revenueData['total_transactions']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Average</div>
                    <div class="text-3xl font-bold text-gray-900">₱{{ number_format($revenueData['average_transaction'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Payouts</div>
                    <div class="text-3xl font-bold text-accent-600">₱{{ number_format($revenueData['lawyer_payout'], 2) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">Daily Revenue</h3>
                    <div id="revenueChart"></div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">By Type</h3>
                    <div id="typeChart"></div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:navigated', () => {
                renderCharts();
            });
            
            if (document.readyState === 'complete') {
                renderCharts();
            } else {
                window.addEventListener('load', renderCharts);
            }

            function renderCharts() {
                // Revenue Chart
                if (document.getElementById('revenueChart')) {
                    const revenueChart = new ApexCharts(document.getElementById('revenueChart'), {
                        series: [{
                            name: 'Revenue',
                            data: @json($revenueData['daily_revenue']->pluck('total'))
                        }],
                        chart: { type: 'area', height: 300, toolbar: { show: false } },
                        colors: ['#1E3A8A'],
                        xaxis: { categories: @json($revenueData['daily_revenue']->pluck('date')) },
                        yaxis: { labels: { formatter: (val) => '₱' + val.toLocaleString() } },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } }
                    });
                    revenueChart.render();
                }

                // Type Chart
                if (document.getElementById('typeChart')) {
                    const typeChart = new ApexCharts(document.getElementById('typeChart'), {
                        series: @json($revenueData['by_type']->pluck('total')),
                        chart: { type: 'donut', height: 300 },
                        labels: @json($revenueData['by_type']->pluck('type')->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))),
                        colors: ['#1E3A8A', '#B91C1C', '#16A34A', '#EA580C'],
                        legend: { position: 'bottom' }
                    });
                    typeChart.render();
                }
            }
        </script>
    @endif
</div>
