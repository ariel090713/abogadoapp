<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <!-- Revenue Report -->
    @if($reportType === 'revenue' && $revenueData)
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Revenue</div>
                    <div class="text-3xl font-bold text-primary-700">₱{{ number_format($revenueData['total_revenue'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Transactions</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($revenueData['total_transactions']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Average Transaction</div>
                    <div class="text-3xl font-bold text-gray-900">₱{{ number_format($revenueData['average_transaction'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Lawyer Payouts</div>
                    <div class="text-3xl font-bold text-accent-600">₱{{ number_format($revenueData['lawyer_payout'], 2) }}</div>
                </div>
            </div>

            <!-- Daily Revenue Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Daily Revenue</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($revenueData['daily_revenue'] as $day)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $day->count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($day->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue by Type Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Revenue Breakdown by Type</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($revenueData['by_type'] as $type)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $type->type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $type->count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($type->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱{{ number_format($type->total / $type->count, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Consultation Report -->
    @if($reportType === 'consultations' && $consultationData)
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Consultations</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($consultationData['total_consultations']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Completed</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($consultationData['completed']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ number_format($consultationData['pending']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Completion Rate</div>
                    <div class="text-3xl font-bold text-primary-700">{{ $consultationData['completion_rate'] }}%</div>
                </div>
            </div>

            <!-- By Status Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Consultations by Status</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($consultationData['by_status'] as $status)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ucfirst($status->status) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $status->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- By Type Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Consultations by Type</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($consultationData['by_type'] as $type)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $type->consultation_type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $type->count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($type->avg_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Lawyer Performance Report -->
    @if($reportType === 'lawyer_performance' && $lawyerPerformanceData)
        <div class="space-y-6">
            <!-- Top Performers Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Top 10 Lawyers by Revenue</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lawyer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consultations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Earnings</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Response Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($lawyerPerformanceData['top_earners'] as $index => $lawyer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        #{{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $lawyer['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $lawyer['email'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $lawyer['completed_consultations'] }}/{{ $lawyer['total_consultations'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱{{ number_format($lawyer['total_revenue'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                        ₱{{ number_format($lawyer['total_earnings'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ number_format($lawyer['average_rating'], 2) }} ⭐ ({{ $lawyer['total_reviews'] }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ number_format($lawyer['response_rate'], 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Client Activity Report -->
    @if($reportType === 'client_activity' && $clientActivityData)
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Clients</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($clientActivityData['total_clients']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Active Clients (30 days)</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($clientActivityData['active_clients']) }}</div>
                </div>
            </div>

            <!-- Top Spenders -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Top 10 Spenders</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consultations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Activity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($clientActivityData['top_spenders'] as $client)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $client['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $client['email'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $client['total_consultations'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                        ₱{{ number_format($client['total_spent'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $client['last_activity'] ? $client['last_activity']->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Transaction Report -->
    @if($reportType === 'transactions' && $transactionData)
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Transactions</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($transactionData['total_transactions']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Completed</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($transactionData['completed_transactions']) }}</div>
                    <div class="text-sm text-gray-600 mt-1">₱{{ number_format($transactionData['completed_amount'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ number_format($transactionData['pending_transactions']) }}</div>
                    <div class="text-sm text-gray-600 mt-1">₱{{ number_format($transactionData['pending_amount'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Refunded</div>
                    <div class="text-3xl font-bold text-red-600">{{ number_format($transactionData['refunded_transactions']) }}</div>
                    <div class="text-sm text-gray-600 mt-1">₱{{ number_format($transactionData['refunded_amount'], 2) }}</div>
                </div>
            </div>

            <!-- Transaction List -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lawyer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($transactionData['transactions']->take(50) as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $transaction->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->user?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->lawyer?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱{{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status === 'refunded') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Platform Metrics -->
    @if($reportType === 'platform_metrics' && $platformMetrics)
        <div class="space-y-6">
            <!-- User Growth Stats -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">User Growth</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">New Users</div>
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($platformMetrics['new_users']) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">New Lawyers</div>
                        <div class="text-3xl font-bold text-primary-700">{{ number_format($platformMetrics['new_lawyers']) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">New Clients</div>
                        <div class="text-3xl font-bold text-accent-600">{{ number_format($platformMetrics['new_clients']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Financial Overview -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Financial Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Total Revenue</div>
                        <div class="text-3xl font-bold text-green-600">₱{{ number_format($platformMetrics['total_revenue'], 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Refund Amount</div>
                        <div class="text-3xl font-bold text-red-600">₱{{ number_format($platformMetrics['refund_amount'], 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Total Payouts</div>
                        <div class="text-3xl font-bold text-blue-600">₱{{ number_format($platformMetrics['total_payouts'], 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Net Revenue</div>
                        <div class="text-3xl font-bold text-primary-700">₱{{ number_format($platformMetrics['net_revenue'], 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Activity Metrics -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Platform Activity</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Total Consultations</div>
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($platformMetrics['total_consultations']) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Document Requests</div>
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($platformMetrics['total_document_requests']) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Total Refunds</div>
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($platformMetrics['total_refunds']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Refund Report -->
    @if($reportType === 'refunds' && $refundData)
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Refunds</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($refundData['total_refunds']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                    <div class="text-3xl font-bold text-red-600">₱{{ number_format($refundData['total_amount'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Completed</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($refundData['completed_refunds']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ number_format($refundData['pending_refunds']) }}</div>
                </div>
            </div>

            <!-- By Reason -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Refunds by Reason</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($refundData['by_reason'] as $reason => $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $reason)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data['count'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        ₱{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Payout Report -->
    @if($reportType === 'payouts' && $payoutData)
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Payouts</div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($payoutData['total_payouts']) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                    <div class="text-3xl font-bold text-blue-600">₱{{ number_format($payoutData['total_amount'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Completed</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($payoutData['completed_payouts']) }}</div>
                    <div class="text-sm text-gray-600 mt-1">₱{{ number_format($payoutData['completed_amount'], 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-1">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ number_format($payoutData['pending_payouts']) }}</div>
                    <div class="text-sm text-gray-600 mt-1">₱{{ number_format($payoutData['pending_amount'], 2) }}</div>
                </div>
            </div>

            <!-- Payout List -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Payouts</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lawyer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payoutData['payouts']->take(50) as $payout)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $payout->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $payout->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payout->lawyer?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱{{ number_format($payout->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $payout->transactions->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($payout->status === 'completed') bg-green-100 text-green-800
                                            @elseif($payout->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($payout->status === 'processing') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($payout->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
