<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">FAQ Management</h1>
            <p class="text-gray-600 mt-1">Manage frequently asked questions</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create/Edit Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            {{ $editingId ? 'Edit FAQ' : 'Create New FAQ' }}
        </h2>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select wire:model="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="general">General</option>
                        <option value="consultations">Consultations</option>
                        <option value="payments">Payments</option>
                        <option value="documents">Documents</option>
                        <option value="lawyers">For Lawyers</option>
                        <option value="clients">For Clients</option>
                    </select>
                    @error('category') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input 
                        type="number" 
                        wire:model="order" 
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('order') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Question -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                <input 
                    type="text" 
                    wire:model="question" 
                    placeholder="Enter the question"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                @error('question') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Answer -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Answer</label>
                <textarea 
                    wire:model="answer" 
                    rows="5"
                    placeholder="Enter the answer"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                ></textarea>
                @error('answer') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Published Status -->
            <div class="flex items-center gap-3">
                <input 
                    type="checkbox" 
                    wire:model="is_published" 
                    id="is_published"
                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                >
                <label for="is_published" class="text-sm font-medium text-gray-700">
                    Publish this FAQ
                </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button 
                    type="submit"
                    class="px-6 py-2.5 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="save">
                        {{ $editingId ? 'Update FAQ' : 'Create FAQ' }}
                    </span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>

                @if($editingId)
                    <button 
                        type="button"
                        wire:click="cancelEdit"
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
                    >
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Search FAQs..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select wire:model.live="filterCategory" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="all">All Categories</option>
                    <option value="general">General</option>
                    <option value="consultations">Consultations</option>
                    <option value="payments">Payments</option>
                    <option value="documents">Documents</option>
                    <option value="lawyers">For Lawyers</option>
                    <option value="clients">For Clients</option>
                </select>
            </div>
        </div>
    </div>

    <!-- FAQs List -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $faq->order }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-md">
                                    <div class="font-medium">{{ $faq->question }}</div>
                                    <div class="text-gray-500 mt-1 line-clamp-2">{{ Str::limit($faq->answer, 100) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full capitalize">
                                    {{ $faq->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button 
                                    wire:click="togglePublish({{ $faq->id }})"
                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $faq->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"
                                >
                                    {{ $faq->is_published ? 'Published' : 'Draft' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    wire:click="edit({{ $faq->id }})"
                                    class="text-primary-600 hover:text-primary-900 mr-3"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="delete({{ $faq->id }})"
                                    wire:confirm="Are you sure you want to delete this FAQ?"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No FAQs found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $faqs->links() }}
        </div>
    </div>
</div>
