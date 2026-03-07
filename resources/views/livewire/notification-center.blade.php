<div>
    <!-- Bell Icon Button -->
    <button 
        wire:click="toggleSidebar"
        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        <!-- Unread Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Backdrop Overlay -->
    @if($isOpen)
        <div 
            wire:click="toggleSidebar"
            class="fixed inset-0 bg-opacity-50 z-40 transition-opacity"
            x-data
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>
    @endif

    <!-- Notification Sidebar (Full Height) -->
    <div 
        class="fixed top-0 right-0 h-screen w-full md:w-96 bg-white shadow-2xl z-50 transform transition-transform duration-300 ease-in-out flex flex-col"
        style="transform: translateX({{ $isOpen ? '0' : '100%' }})">
        
        <!-- Header (Sticky) -->
        <div class="flex-shrink-0 border-b border-gray-200 bg-white">
            <div class="flex items-center justify-between p-4">
                <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
                <div class="flex items-center gap-2">
                    @if($unreadCount > 0)
                        <button 
                            wire:click="markAllAsRead"
                            class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Mark All Read
                        </button>
                    @endif
                    <button 
                        wire:click="toggleSidebar"
                        class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="flex border-b border-gray-200">
                <button 
                    wire:click="setFilter('all')"
                    class="flex-1 px-4 py-3 text-sm font-medium transition {{ $filter === 'all' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' }}">
                    All
                </button>
                <button 
                    wire:click="setFilter('unread')"
                    class="flex-1 px-4 py-3 text-sm font-medium transition {{ $filter === 'unread' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' }}">
                    Unread
                    @if($unreadCount > 0)
                        <span class="ml-1 px-2 py-0.5 text-xs bg-red-100 text-red-600 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </button>
                <button 
                    wire:click="setFilter('read')"
                    class="flex-1 px-4 py-3 text-sm font-medium transition {{ $filter === 'read' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' }}">
                    Read
                </button>
            </div>
        </div>

        <!-- Notifications List (Scrollable) -->
        <div class="flex-1 overflow-y-auto">
            @if($notifications->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-center p-8">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No notifications</p>
                    <p class="text-sm text-gray-400 mt-1">You're all caught up!</p>
                </div>
            @else
                @php
                    $groupedNotifications = $notifications->groupBy(function($notification) {
                        $date = $notification->created_at;
                        if ($date->isToday()) return 'Today';
                        if ($date->isYesterday()) return 'Yesterday';
                        if ($date->isCurrentWeek()) return 'This Week';
                        return $date->format('F Y');
                    });
                @endphp

                @foreach($groupedNotifications as $group => $groupNotifications)
                    <!-- Date Group Header -->
                    <div class="sticky top-0 bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider z-10">
                        {{ $group }}
                    </div>

                    <!-- Notifications in Group -->
                    @foreach($groupNotifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                            $message = $data['message'] ?? 'New notification';
                            $actionUrl = $data['action_url'] ?? '#';
                        @endphp

                        <div 
                            wire:click="navigateTo('{{ $notification->id }}', '{{ $actionUrl }}')"
                            class="border-b border-gray-100 p-4 hover:bg-gray-50 transition cursor-pointer {{ $isUnread ? 'bg-blue-50' : '' }}">
                            <div class="flex items-start gap-3">
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 {{ $isUnread ? 'font-semibold' : '' }}">
                                        {{ $message }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0 flex items-center gap-2">
                                    @if($isUnread)
                                        <button 
                                            wire:click.stop="markAsRead('{{ $notification->id }}')"
                                            class="p-1 text-blue-600 hover:bg-blue-100 rounded transition"
                                            title="Mark as read">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @endif
                                    <button 
                                        wire:click.stop="deleteNotification('{{ $notification->id }}')"
                                        class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                <!-- Load More (if needed) -->
                @if($notifications->count() >= 20)
                    <div class="p-4 text-center">
                        <button class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Load More
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
    // Listen for new notifications via Pusher
    document.addEventListener('livewire:init', () => {
        @if(auth()->check())
            // Pusher integration will go here
            // Echo.private('user.{{ auth()->id() }}')
            //     .listen('.notification', (data) => {
            //         Livewire.dispatch('refreshNotifications');
            //     });
        @endif
    });
</script>
