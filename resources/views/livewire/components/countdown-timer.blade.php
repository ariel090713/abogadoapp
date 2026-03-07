<div 
    x-data="{ 
        timeRemaining: @entangle('timeRemaining'),
        expired: @entangle('expired'),
        urgency: @entangle('urgency'),
        deadline: '{{ $deadline->toIso8601String() }}',
        updateInterval: null,
        
        init() {
            this.startCountdown();
        },
        
        startCountdown() {
            this.updateInterval = setInterval(() => {
                const now = new Date();
                const deadlineDate = new Date(this.deadline);
                const diff = deadlineDate - now;
                
                if (diff <= 0) {
                    this.expired = true;
                    this.urgency = 'expired';
                    this.timeRemaining = { formatted: 'Expired', total_seconds: 0 };
                    clearInterval(this.updateInterval);
                    $wire.calculateTimeRemaining();
                    return;
                }
                
                const totalSeconds = Math.floor(diff / 1000);
                const totalHours = Math.floor(totalSeconds / 3600);
                const days = Math.floor(totalHours / 24);
                const hours = totalHours % 24;
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                // Format time
                let formatted;
                if (totalHours >= 24) {
                    formatted = `${days} days ${hours} hours`;
                } else if (totalHours >= 1) {
                    formatted = `${hours} hours ${minutes} minutes`;
                } else if (minutes >= 1) {
                    formatted = `${minutes} minutes`;
                } else {
                    formatted = `${seconds} seconds`;
                }
                
                // Update urgency
                if (totalHours > 12) {
                    this.urgency = 'safe';
                } else if (totalHours > 3) {
                    this.urgency = 'warning';
                } else {
                    this.urgency = 'urgent';
                }
                
                this.timeRemaining = { formatted, total_seconds: totalSeconds };
            }, 1000);
        },
        
        destroy() {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
            }
        }
    }"
    x-init="init()"
    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium"
    :class="{
        'bg-green-50 text-green-700 border border-green-200': urgency === 'safe',
        'bg-yellow-50 text-yellow-700 border border-yellow-200': urgency === 'warning',
        'bg-red-50 text-red-700 border border-red-200': urgency === 'urgent',
        'bg-gray-100 text-gray-500 border border-gray-200': urgency === 'expired'
    }"
>
    <!-- Icon -->
    <svg 
        class="w-4 h-4" 
        :class="{
            'text-green-600': urgency === 'safe',
            'text-yellow-600': urgency === 'warning',
            'text-red-600': urgency === 'urgent',
            'text-gray-400': urgency === 'expired'
        }"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
    >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    
    <!-- Label and Time -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-1">
        <span class="text-xs opacity-75">{{ $label }}:</span>
        <span class="font-semibold" x-text="timeRemaining.formatted"></span>
    </div>
</div>
