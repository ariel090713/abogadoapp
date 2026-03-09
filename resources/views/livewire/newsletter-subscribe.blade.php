<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-xl">
            {{ session('info') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
        <input 
            type="email" 
            wire:model="email"
            placeholder="Enter your email address" 
            class="flex-1 px-6 py-4 rounded-xl text-gray-900 bg-white border-2 @error('email') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            required
        >
        <button 
            type="submit" 
            class="px-8 py-4 bg-primary-700 text-white rounded-xl font-semibold hover:bg-primary-800 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="subscribe">Subscribe</span>
            <span wire:loading wire:target="subscribe">Subscribing...</span>
        </button>
    </form>
    
    @error('email')
        <p class="text-red-600 text-sm mt-2 text-center">{{ $message }}</p>
    @enderror
</div>
