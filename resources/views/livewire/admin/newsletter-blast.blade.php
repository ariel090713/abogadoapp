<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Newsletter Blast</h1>
        <p class="text-gray-600 mt-1">Send email updates to all subscribers</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Subscribers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $subscriberCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Subscribers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalSubscribers }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Unsubscribed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $unsubscribedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Newsletter Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Compose Newsletter</h2>

        <form wire:submit.prevent="sendNewsletter" class="space-y-6">
            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <input 
                    type="text" 
                    id="subject"
                    wire:model="subject"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('subject') border-red-500 @enderror"
                    placeholder="Enter email subject"
                >
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message with TinyMCE -->
            <div wire:ignore>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea 
                    id="tinymce-editor"
                    class="w-full"
                ></textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button 
                    type="button"
                    onclick="updatePreview()"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition"
                >
                    {{ $showPreview ? 'Hide Preview' : 'Show Preview' }}
                </button>
                <button 
                    type="submit"
                    onclick="syncContent()"
                    class="px-6 py-3 bg-primary-700 text-white rounded-lg font-medium hover:bg-primary-800 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="sendNewsletter">Send to {{ $subscriberCount }} Subscribers</span>
                    <span wire:loading wire:target="sendNewsletter">Sending...</span>
                </button>
            </div>
        </form>

        <!-- Preview -->
        @if($showPreview)
            <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Email Preview</h3>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <!-- Email Container -->
                    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                        <!-- Header -->
                        <div class="bg-primary-800 p-8 text-center">
                            <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo Logo" class="w-20 h-20 mx-auto mb-3">
                            <h1 class="text-white text-2xl font-bold">AbogadoMo</h1>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-8">
                            <div class="prose max-w-none text-gray-700">
                                @if($message)
                                    {!! $message !!}
                                @else
                                    <p class="text-gray-400 italic">No content yet. Start typing in the editor above.</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="bg-gray-50 p-8 text-center border-t border-gray-200">
                            <p class="text-gray-600 text-sm mb-4">
                                Thank you for being a valued subscriber!
                            </p>
                            <a href="#" class="inline-block px-6 py-3 bg-primary-800 text-white rounded-lg font-semibold mb-5 hover:bg-primary-900 transition">
                                Visit AbogadoMo
                            </a>
                            <p class="text-gray-500 text-xs mb-2">
                                If you wish to unsubscribe from our newsletter, click the link below.
                            </p>
                            <a href="#" class="text-accent-500 text-xs underline hover:text-accent-600">
                                Unsubscribe
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    let editor;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for TinyMCE to be fully loaded
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE not loaded');
            return;
        }
        
        tinymce.init({
            selector: '#tinymce-editor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }',
            images_upload_handler: function (blobInfo, progress) {
                return new Promise(function(resolve, reject) {
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('admin.tinymce.upload') }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                    
                    xhr.upload.onprogress = function(e) {
                        progress(e.loaded / e.total * 100);
                    };
                    
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const result = JSON.parse(xhr.responseText);
                                if (result.location) {
                                    resolve(result.location);
                                } else {
                                    reject('Upload failed: ' + (result.error || 'Unknown error'));
                                }
                            } catch (e) {
                                reject('Upload failed: Invalid response');
                            }
                        } else {
                            reject('Upload failed: HTTP ' + xhr.status);
                        }
                    };
                    
                    xhr.onerror = function() {
                        reject('Upload failed: Network error');
                    };
                    
                    xhr.send(formData);
                });
            },
            setup: function(ed) {
                editor = ed;
                ed.on('change', function() {
                    const content = ed.getContent();
                    @this.message = content;
                });
                ed.on('init', function() {
                    ed.setContent(@this.message || '');
                });
            }
        });
    });
    
    function syncContent() {
        if (editor) {
            @this.message = editor.getContent();
        }
    }
    
    function updatePreview() {
        if (editor) {
            @this.message = editor.getContent();
        }
        @this.call('togglePreview');
    }
</script>
@endpush
