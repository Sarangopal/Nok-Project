@php
    use App\Models\RenewalReminder;
    
    $totalSent = RenewalReminder::where('status', 'sent')->count();
    $totalFailed = RenewalReminder::where('status', 'failed')->count();
    $sentToday = RenewalReminder::where('status', 'sent')->whereDate('created_at', today())->count();
    $sentThisWeek = RenewalReminder::where('status', 'sent')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
    $sentThisMonth = RenewalReminder::where('status', 'sent')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
    
    $byDays = RenewalReminder::selectRaw('days_before_expiry, count(*) as count')
        ->groupBy('days_before_expiry')
        ->orderBy('days_before_expiry', 'desc')
        ->get();
@endphp

<div class="space-y-6">
    <!-- Overview Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
            <div class="text-sm text-green-600 dark:text-green-400">Total Sent</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ number_format($totalSent) }}</div>
        </div>

        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
            <div class="text-sm text-red-600 dark:text-red-400">Failed</div>
            <div class="text-2xl font-bold text-red-700 dark:text-red-300">{{ number_format($totalFailed) }}</div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
            <div class="text-sm text-blue-600 dark:text-blue-400">Today</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($sentToday) }}</div>
        </div>

        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
            <div class="text-sm text-purple-600 dark:text-purple-400">This Month</div>
            <div class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ number_format($sentThisMonth) }}</div>
        </div>
    </div>

    <!-- Time-based Stats -->
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">📊 Time-based Statistics</h3>
        <dl class="grid grid-cols-3 gap-4">
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Today</dt>
                <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sentToday) }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">This Week</dt>
                <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sentThisWeek) }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">This Month</dt>
                <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sentThisMonth) }}</dd>
            </div>
        </dl>
    </div>

    <!-- Reminder Type Distribution -->
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">📧 Reminders by Type</h3>
        <div class="space-y-2">
            @foreach($byDays as $stat)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        @if($stat->days_before_expiry === 0)
                            🔴 Expired Today
                        @elseif($stat->days_before_expiry === 1)
                            🟡 1 Day Before
                        @elseif($stat->days_before_expiry === 7)
                            🟠 7 Days Before
                        @elseif($stat->days_before_expiry === 15)
                            🔵 15 Days Before
                        @elseif($stat->days_before_expiry === 30)
                            🟢 30 Days Before
                        @else
                            {{ $stat->days_before_expiry }} Days Before
                        @endif
                    </span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($stat->count) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Success Rate -->
    @php
        $total = $totalSent + $totalFailed;
        $successRate = $total > 0 ? ($totalSent / $total) * 100 : 0;
    @endphp
    <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">✅ Success Rate</h3>
        <div class="flex items-end gap-2">
            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($successRate, 1) }}%</span>
            <span class="text-sm text-gray-600 dark:text-gray-400 mb-1">({{ number_format($totalSent) }} / {{ number_format($total) }})</span>
        </div>
        <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-green-500 to-green-600" style="width: {{ $successRate }}%"></div>
        </div>
    </div>
</div>








