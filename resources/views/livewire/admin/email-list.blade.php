<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Email List</h1>
            <p class="text-gray-600 mt-1">Manage newsletter subscribers</p>
        </div>
        <button 
            wire:click="$set('showAddModal', true)"
            class="px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition"
        >
            + Add Email
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search -->
    <div class="mb-6">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search"
            placeholder="Search emails..."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
        >
    </div>

    <!-- Email List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscribed</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $subscriber->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $subscriber->is_subscribed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $subscriber->is_subscribed ? 'Active' : 'Unsubscribed' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $subscriber->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button 
                                wire:click="toggleSubscription({{ $subscriber->id }})"
                                class="text-blue-600 hover:text-blue-800 text-sm"
                            >
                                {{ $subscriber->is_subscribed ? 'Unsubscribe' : 'Subscribe' }}
                            </button>
                            <button 
                                wire:click="deleteSubscriber({{ $subscriber->id }})"
                                wire:confirm="Are you sure you want to delete this email?"
                                class="text-red-600 hover:text-red-800 text-sm"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No subscribers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $subscribers->links() }}
    </div>

    <!-- Add Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Add Email Subscriber</h3>
                <form wire:submit.prevent="addSubscriber">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            wire:model="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror"
                            placeholder="email@example.com"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex gap-3">
                        <button 
                            type="button"
                            wire:click="$set('showAddModal', false)"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800"
                        >
                            Add Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
