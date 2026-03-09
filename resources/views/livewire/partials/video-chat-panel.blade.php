<div class="flex flex-col h-full overflow-hidden">
<!-- Header - Desktop only -->
<div class="hidden lg:block flex-shrink-0 px-4 py-3 border-b border-gray-200">
    <h3 class="font-semibold text-gray-900">Chat</h3>
</div>

<!-- Messages Container -->
<div id="messages-container" class="flex-1 overflow-y-auto px-4 py-3" wire:ignore>
    <div id="messages-wrapper" class="space-y-3">
        <!-- Messages will be rendered here via JavaScript -->
    </div>
</div>

<script>
    // Scroll to bottom helper function (defined first)
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
    
    // Initial messages from server
    let messages = @json($messages);
    
    // Render all messages
    function renderMessages() {
        const wrapper = document.getElementById('messages-wrapper');
        if (!wrapper) return;
        
        wrapper.innerHTML = messages.map(message => renderMessage(message)).join('');
        scrollToBottom();
    }
    
    // Render single message
    function renderMessage(message) {
        const isMe = message.is_mine;
        const avatar = message.sender_avatar 
            ? `<img src="${message.sender_avatar}" alt="${message.sender_name}" class="w-full h-full rounded-full object-cover">`
            : `<span class="text-xs font-medium text-gray-600">${message.sender_name.charAt(0)}</span>`;
        
        const attachments = message.attachments 
            ? message.attachments.map(att => `
                <a href="${att.url}" target="_blank" class="flex items-center gap-2 text-sm ${isMe ? 'text-blue-100 hover:text-white' : 'text-blue-600 hover:text-blue-800'}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    📎 File attachment
                </a>
            `).join('')
            : '';
        
        return `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                <div class="max-w-[80%]">
                    <div class="flex items-start gap-2 ${isMe ? 'flex-row-reverse' : ''}">
                        ${!isMe ? `
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">
                                ${avatar}
                            </div>
                        ` : ''}
                        
                        <div>
                            ${!isMe ? `<p class="text-xs text-gray-500 mb-1">${message.sender_name}</p>` : ''}
                            
                            <div class="rounded-2xl px-4 py-2 ${isMe ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900'}">
                                ${message.message ? `<p class="text-sm break-words">${message.message}</p>` : ''}
                                ${attachments ? `<div class="mt-2 space-y-1">${attachments}</div>` : ''}
                            </div>
                            
                            <p class="text-xs text-gray-400 mt-1 ${isMe ? 'text-right' : ''}">
                                ${message.created_at}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Append new message
    function appendMessage(message) {
        messages.push(message);
        const wrapper = document.getElementById('messages-wrapper');
        if (wrapper) {
            wrapper.insertAdjacentHTML('beforeend', renderMessage(message));
            scrollToBottom();
        }
    }
    
    // Scroll to bottom with delay to ensure content is rendered
    (function() {
        setTimeout(function() {
            renderMessages();
        }, 300);
    })();
    
    // Listen for new messages from Livewire
    window.addEventListener('message-appended-to-dom', function(event) {
        if (event.detail && event.detail.message) {
            appendMessage(event.detail.message);
        }
    });
</script>

<!-- Message Input -->
<div class="flex-shrink-0 px-4 py-3 border-t border-gray-200 bg-white">
    <!-- Debug: Status = {{ $videoStatus }} -->
    <form wire:submit.prevent="sendMessage" class="space-y-3">
        <!-- File Attachments Preview -->
        @if(!empty($attachments))
        <div class="flex flex-wrap gap-2">
            @foreach($attachments as $index => $attachment)
            <div class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 text-sm">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <span class="text-gray-700">{{ $attachment->getClientOriginalName() }}</span>
                <button type="button" wire:click="removeAttachment({{ $index }})" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
        @endif

        <div class="flex items-end gap-2">
            <!-- File Attachment Button -->
            <label class="flex-shrink-0 cursor-pointer">
                <input type="file" wire:model="attachments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                <div class="w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
            </label>

            <!-- Message Input -->
            <textarea 
                wire:model="newMessage" 
                placeholder="Type a message..."
                rows="1"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"
            ></textarea>

            <!-- Send Button -->
            <button 
                type="submit" 
                class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-600 hover:bg-blue-700 flex items-center justify-center text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
            >
                <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <svg wire:loading wire:target="sendMessage" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
