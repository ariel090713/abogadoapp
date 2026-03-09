<?php

namespace App\Livewire;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Services\BroadcastingService;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConsultationChat extends Component
{
    use WithFileUploads;

    public Consultation $consultation;
    public $messages = [];
    public $newMessage = '';
    public $attachments = [];
    public $isTyping = false;
    public $otherUserTyping = false;
    public $chatStatus = 'waiting'; // waiting, active, ended
    
    // Completion modal
    public $showCompleteModal = false;
    public $completionNotes = '';
    public $reviewedDocument;

    public function mount(Consultation $consultation)
    {
        // Verify user has access to this consultation
        if (!$this->canAccessChat()) {
            abort(403, 'Unauthorized access to consultation chat');
        }

        $this->consultation = $consultation;
        
        // Auto-update status based on time
        $this->autoUpdateConsultationStatus();
        
        $this->updateChatStatus();
        $this->loadMessages();
        
        if ($this->chatStatus === 'active') {
            $this->markMessagesAsRead();
        }
    }

    public function autoUpdateConsultationStatus()
    {
        $now = now();
        $scheduledAt = $this->consultation->scheduled_at;
        
        if (!$scheduledAt) {
            return;
        }

        $endTime = $scheduledAt->copy()->addMinutes($this->consultation->duration);

        // Update to in_progress if time has started
        if ($this->consultation->status === 'scheduled' && $now->gte($scheduledAt) && $now->lt($endTime)) {
            $this->consultation->update([
                'status' => 'in_progress',
                'started_at' => $this->consultation->started_at ?? now(),
            ]);
            $this->consultation->refresh();
        }
        
        // Update to ended if time has passed (but not completed yet)
        if (in_array($this->consultation->status, ['scheduled', 'in_progress']) && $now->gte($endTime)) {
            $this->consultation->update([
                'ended_at' => $this->consultation->ended_at ?? $endTime,
            ]);
            $this->consultation->refresh();
        }
    }

    public function updateChatStatus()
    {
        $now = now();
        $scheduledAt = $this->consultation->scheduled_at;
        
        if (!$scheduledAt) {
            $this->chatStatus = 'waiting';
            return;
        }

        // Calculate end time based on duration
        $endTime = $scheduledAt->copy()->addMinutes($this->consultation->duration);

        if ($now->lt($scheduledAt)) {
            $this->chatStatus = 'waiting';
        } elseif ($now->gte($scheduledAt) && $now->lt($endTime)) {
            $this->chatStatus = 'active';
        } else {
            $this->chatStatus = 'ended';
        }
    }

    public function getTimeUntilStartProperty()
    {
        if (!$this->consultation->scheduled_at) {
            return null;
        }
        
        return now()->diffInSeconds($this->consultation->scheduled_at, false);
    }

    public function getTimeRemainingProperty()
    {
        if (!$this->consultation->scheduled_at) {
            return null;
        }
        
        $endTime = $this->consultation->scheduled_at->copy()->addMinutes($this->consultation->duration);
        return now()->diffInSeconds($endTime, false);
    }

    public function canAccessChat(): bool
    {
        $userId = auth()->id();
        return $this->consultation->client_id === $userId || 
               $this->consultation->lawyer_id === $userId;
    }

    public function loadMessages()
    {
        $fileService = app(\App\Services\FileUploadService::class);
        
        $this->messages = $this->consultation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($fileService) {
                // Generate temporary signed URLs for attachments
                $attachmentsWithUrls = null;
                if ($message->attachments) {
                    $attachmentsWithUrls = collect($message->attachments)->map(function ($attachment) use ($fileService) {
                        return array_merge($attachment, [
                            'url' => $fileService->getPrivateUrl($attachment['path'], 60),
                        ]);
                    })->toArray();
                }
                
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'sender_avatar' => $message->sender->profile_photo_url ?? null,
                    'message' => $message->message,
                    'attachments' => $attachmentsWithUrls,
                    'read_at' => $message->read_at?->toIso8601String(),
                    'created_at' => $message->created_at->format('M d, Y h:i A'),
                    'is_mine' => $message->sender_id === auth()->id(),
                ];
            })
            ->toArray();
    }

    public function sendMessage(BroadcastingService $broadcasting, FileUploadService $fileService)
    {
        // SECURITY: Verify user has access to this consultation
        if (!$this->canAccessChat()) {
            Log::warning('Unauthorized message send attempt', [
                'consultation_id' => $this->consultation->id,
                'user_id' => auth()->id(),
            ]);
            session()->flash('error', 'Unauthorized access.');
            return;
        }

        // SECURITY: Check if chat is active
        $this->updateChatStatus();
        
        if ($this->chatStatus !== 'active') {
            session()->flash('error', 'Chat is not active yet or has ended.');
            return;
        }

        // THROTTLING: Check message rate limit (max 10 messages per minute)
        $recentMessagesCount = ConsultationMessage::where('consultation_id', $this->consultation->id)
            ->where('sender_id', auth()->id())
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        if ($recentMessagesCount >= 10) {
            Log::warning('Message rate limit exceeded', [
                'consultation_id' => $this->consultation->id,
                'user_id' => auth()->id(),
                'count' => $recentMessagesCount,
            ]);
            session()->flash('error', 'You are sending messages too quickly. Please wait a moment.');
            return;
        }

        // SECURITY: Validate input with strict rules
        $this->validate([
            'newMessage' => 'required_without:attachments|string|max:5000',
            'attachments' => 'nullable|array|max:3', // Max 3 files at once
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png', // 10MB max, specific types only
        ], [
            'attachments.max' => 'You can only upload up to 3 files at once.',
            'attachments.*.max' => 'Each file must not exceed 10MB.',
            'attachments.*.mimes' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.',
        ]);

        // SECURITY: Sanitize message content
        $sanitizedMessage = $this->newMessage ? strip_tags($this->newMessage) : null;

        try {
            // Upload attachments if any (to PRIVATE bucket)
            $uploadedAttachments = [];
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $attachment) {
                    // SECURITY: Additional file validation
                    $extension = strtolower($attachment->getClientOriginalExtension());
                    $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                    
                    if (!in_array($extension, $allowedExtensions)) {
                        Log::warning('Invalid file type attempted', [
                            'consultation_id' => $this->consultation->id,
                            'user_id' => auth()->id(),
                            'extension' => $extension,
                        ]);
                        continue; // Skip invalid files
                    }

                    $fileData = $fileService->uploadPrivate(
                        $attachment,
                        'consultation-chat-attachments'
                    );
                    
                    $uploadedAttachments[] = [
                        'path' => $fileData['path'],
                        'original_name' => $fileData['original_name'],
                        'size' => $fileData['size'],
                        'mime_type' => $fileData['mime_type'],
                    ];
                }
            }

            // Create message
            $message = ConsultationMessage::create([
                'consultation_id' => $this->consultation->id,
                'sender_id' => auth()->id(),
                'message' => $sanitizedMessage,
                'attachments' => !empty($uploadedAttachments) ? $uploadedAttachments : null,
            ]);

            // Load sender relationship
            $message->load('sender');

            // Broadcast message
            $broadcasting->broadcastMessage($message);

            // Add to local messages array with temporary signed URLs
            $attachmentsWithUrls = null;
            if ($message->attachments) {
                $attachmentsWithUrls = collect($message->attachments)->map(function ($attachment) use ($fileService) {
                    return array_merge($attachment, [
                        'url' => $fileService->getPrivateUrl($attachment['path'], 60),
                    ]);
                })->toArray();
            }

            $this->messages[] = [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'sender_avatar' => $message->sender->profile_photo_url ?? null,
                'message' => $message->message,
                'attachments' => $attachmentsWithUrls,
                'read_at' => null,
                'created_at' => $message->created_at->format('M d, Y h:i A'),
                'is_mine' => true,
            ];

            // Reset form
            $this->reset(['newMessage', 'attachments']);

            // Dispatch event to scroll to bottom
            $this->dispatch('message-sent');

            Log::info('Message sent successfully', [
                'message_id' => $message->id,
                'consultation_id' => $this->consultation->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send message', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to send message. Please try again.');
        }
    }

    public function updatedNewMessage()
    {
        // Broadcast typing indicator (throttled on frontend)
        $this->isTyping = !empty($this->newMessage);
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            $this->attachments = array_values($this->attachments); // Re-index array
        }
    }


    public function addMessageToUI($messageData)
    {
        // Just dispatch event to JavaScript - let JS handle the DOM update
        // This prevents Livewire from re-rendering the entire messages list
        $this->dispatch('new-message-received', $messageData);
    }

    public function broadcastTyping(BroadcastingService $broadcasting)
    {
        if ($this->isTyping) {
            $broadcasting->broadcastTyping(
                $this->consultation->id,
                auth()->id(),
                auth()->user()->name
            );
        }
    }

    public function markMessagesAsRead(?BroadcastingService $broadcasting = null)
    {
        $unreadMessages = $this->consultation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->get();

        foreach ($unreadMessages as $message) {
            $message->markAsRead();
            
            // Broadcast read status
            if ($broadcasting) {
                $broadcasting->broadcastMessageRead(
                    $this->consultation->id,
                    $message->id,
                    auth()->id()
                );
            }
        }
    }

    public function getOtherUserProperty()
    {
        $userId = auth()->id();
        
        if ($this->consultation->client_id === $userId) {
            return $this->consultation->lawyer;
        }
        
        return $this->consultation->client;
    }

    public function completeConsultation(FileUploadService $fileService)
    {
        // Only lawyer can complete
        if ($this->consultation->lawyer_id !== auth()->id()) {
            session()->flash('error', 'Only the lawyer can mark consultation as complete.');
            $this->showCompleteModal = false;
            return;
        }

        // Validate status
        if (!in_array($this->consultation->status, ['scheduled', 'in_progress'])) {
            session()->flash('error', 'Invalid consultation status.');
            $this->showCompleteModal = false;
            return;
        }

        // Validate that at least one is provided (document or notes)
        $this->validate([
            'completionNotes' => 'nullable|string|max:2000',
            'reviewedDocument' => 'nullable|file|max:10240|mimes:pdf,doc,docx',
        ]);

        // Check if at least one is provided
        if (empty($this->completionNotes) && !$this->reviewedDocument) {
            session()->flash('error', 'Please provide either completion notes or upload a reviewed document.');
            return;
        }

        try {
            $updateData = [
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $this->completionNotes,
            ];

            // Upload reviewed document if provided
            if ($this->reviewedDocument) {
                $fileData = $fileService->uploadPrivate(
                    $this->reviewedDocument,
                    'reviewed-documents'
                );
                
                $updateData['reviewed_document_path'] = $fileData['path'];
            }

            $this->consultation->update($updateData);

            // Send notification to client
            $this->consultation->client->notify(new \App\Notifications\ConsultationCompleted($this->consultation));

            session()->flash('success', 'Consultation marked as completed! Client has been notified.');
            $this->showCompleteModal = false;
            
            // Refresh consultation and update chat status
            $this->consultation->refresh();
            $this->updateChatStatus();
            
        } catch (\Exception $e) {
            Log::error('Consultation completion failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to complete consultation. Please try again.');
            $this->showCompleteModal = false;
        }
    }

    public function markAsCompleted()
    {
        // Only lawyer can mark as completed
        if (!auth()->user()->isLawyer()) {
            session()->flash('error', 'Only the lawyer can mark the consultation as completed.');
            return;
        }

        // Verify this is the lawyer for this consultation
        if ($this->consultation->lawyer_id !== auth()->id()) {
            session()->flash('error', 'You are not authorized to complete this consultation.');
            return;
        }

        // Check if already completed
        if ($this->consultation->status === 'completed') {
            session()->flash('info', 'This consultation is already marked as completed.');
            $this->showCompleteModal = false;
            return;
        }

        // Validate completion notes are required
        $this->validate([
            'completionNotes' => 'required|string|min:10|max:5000',
        ], [
            'completionNotes.required' => 'Completion notes are required.',
            'completionNotes.min' => 'Please provide at least 10 characters of notes.',
            'completionNotes.max' => 'Completion notes cannot exceed 5000 characters.',
        ]);

        try {
            $this->consultation->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $this->completionNotes,
            ]);

            // Notify client
            $this->consultation->client->notify(
                new \App\Notifications\ConsultationCompleted($this->consultation)
            );

            Log::info('Chat consultation marked as completed', [
                'consultation_id' => $this->consultation->id,
                'lawyer_id' => auth()->id(),
            ]);

            session()->flash('success', 'Consultation marked as completed successfully.');
            
            $this->showCompleteModal = false;
            
            // Refresh the page to show updated status
            return redirect()->route('lawyer.consultation.details', $this->consultation->id);

        } catch (\Exception $e) {
            Log::error('Failed to mark consultation as completed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to mark consultation as completed. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.consultation-chat')
            ->layout('layouts.dashboard', [
                'title' => 'Consultation Chat'
            ]);
    }
}
