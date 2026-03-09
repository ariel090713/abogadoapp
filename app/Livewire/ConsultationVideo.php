<?php

namespace App\Livewire;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Services\BroadcastingService;
use App\Services\FileUploadService;
use App\Services\TwilioVideoService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConsultationVideo extends Component
{
    use WithFileUploads;

    public Consultation $consultation;
    public $messages = [];
    public $newMessage = '';
    public $attachments = [];
    public $videoStatus = 'waiting'; // waiting, active, ended
    public $accessToken = null;
    public $otherUser;
    
    // Completion modal
    public $showCompleteModal = false;
    public $completionNotes = '';
    public $reviewedDocument;
    
    // Reschedule modal
    public $showRescheduleModal = false;
    public $selectedDate = null;
    public $availableSlots = [];
    public $selectedSlot = null;
    public $rescheduleReason = '';
    public $declineReason = '';
    public $showDeclineModal = false;

    public function mount(Consultation $consultation)
    {
        try {
            Log::info('Video consultation page accessed', [
                'consultation_id' => $consultation->id,
                'user_id' => auth()->id(),
                'consultation_type' => $consultation->consultation_type,
                'status' => $consultation->status,
            ]);

            // Verify user has access to this consultation
            if (!$this->canAccessVideo()) {
                Log::warning('Unauthorized video access attempt', [
                    'consultation_id' => $consultation->id,
                    'user_id' => auth()->id(),
                ]);
                abort(403, 'Unauthorized access to video consultation');
            }

            // Verify consultation type is video
            if ($consultation->consultation_type !== 'video') {
                Log::warning('Non-video consultation accessed via video route', [
                    'consultation_id' => $consultation->id,
                    'type' => $consultation->consultation_type,
                ]);
                abort(404, 'This is not a video consultation');
            }

            $this->consultation = $consultation;
            
            // Auto-update status based on time
            $this->autoUpdateConsultationStatus();
            
            $this->updateVideoStatus();
            
            Log::info('Video status determined', [
                'consultation_id' => $consultation->id,
                'video_status' => $this->videoStatus,
                'scheduled_at' => $consultation->scheduled_at?->toDateTimeString(),
                'duration' => $consultation->duration,
            ]);
            
            $this->loadMessages();
            
            // Set other user
            $this->otherUser = $this->consultation->client_id === auth()->id() 
                ? $this->consultation->lawyer 
                : $this->consultation->client;
            
            // Generate access token and create room if video is active
            if ($this->videoStatus === 'active') {
                $this->createTwilioRoom();
                $this->generateAccessToken();
                $this->markMessagesAsRead();
            }

        } catch (\Exception $e) {
            Log::error('Error mounting video consultation component', [
                'consultation_id' => $consultation->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
    
    protected function getListeners()
    {
        return [
            'refreshMessages' => 'loadMessages',
            'appendNewMessage',
        ];
    }
    
    public function appendNewMessage($messageId)
    {
        Log::info('appendNewMessage called', [
            'message_id' => $messageId,
            'consultation_id' => $this->consultation->id,
        ]);
        
        $fileService = app(\App\Services\FileUploadService::class);
        
        $message = ConsultationMessage::with('sender')
            ->find($messageId);
        
        if (!$message) {
            Log::warning('Message not found for append', ['message_id' => $messageId]);
            return;
        }
        
        // Generate temporary signed URLs for attachments
        $attachmentsWithUrls = null;
        if ($message->attachments) {
            $attachmentsWithUrls = collect($message->attachments)->map(function ($attachment) use ($fileService) {
                return array_merge($attachment, [
                    'url' => $fileService->getPrivateUrl($attachment['path'], 60),
                ]);
            })->toArray();
        }
        
        $newMessage = [
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
        
        // Append to messages array
        $this->messages[] = $newMessage;
        
        Log::info('Message appended to array', [
            'message_id' => $messageId,
            'total_messages' => count($this->messages),
        ]);
        
        // Dispatch browser event to append message to DOM
        $this->dispatch('message-appended-to-dom', message: $newMessage);
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

    public function updateVideoStatus()
    {
        $now = now();
        $scheduledAt = $this->consultation->scheduled_at;
        
        Log::info('Updating video status', [
            'consultation_id' => $this->consultation->id,
            'now' => $now->toDateTimeString(),
            'scheduled_at' => $scheduledAt?->toDateTimeString(),
            'duration' => $this->consultation->duration,
        ]);
        
        if (!$scheduledAt) {
            Log::warning('No scheduled_at time for consultation', [
                'consultation_id' => $this->consultation->id,
            ]);
            $this->videoStatus = 'waiting';
            return;
        }

        // Calculate end time based on duration
        $endTime = $scheduledAt->copy()->addMinutes($this->consultation->duration);

        if ($now->lt($scheduledAt)) {
            $this->videoStatus = 'waiting';
            Log::info('Video status: waiting', [
                'seconds_until_start' => $now->diffInSeconds($scheduledAt),
            ]);
        } elseif ($now->gte($scheduledAt) && $now->lt($endTime)) {
            $this->videoStatus = 'active';
            Log::info('Video status: active', [
                'seconds_remaining' => $now->diffInSeconds($endTime),
            ]);
        } else {
            $this->videoStatus = 'ended';
            Log::info('Video status: ended', [
                'ended_at' => $endTime->toDateTimeString(),
            ]);
        }
    }

    public function generateAccessToken()
    {
        $twilioService = app(TwilioVideoService::class);
        
        $this->accessToken = $twilioService->generateAccessToken(
            $this->consultation,
            auth()->id(),
            auth()->user()->name
        );
        
        Log::info('Access token generated for video consultation', [
            'consultation_id' => $this->consultation->id,
            'user_id' => auth()->id(),
        ]);
    }
    
    public function createTwilioRoom()
    {
        // Check if room already exists (stored in consultation)
        if ($this->consultation->video_room_sid) {
            Log::info('Twilio room already exists', [
                'consultation_id' => $this->consultation->id,
                'room_sid' => $this->consultation->video_room_sid,
            ]);
            return;
        }
        
        $twilioService = app(TwilioVideoService::class);
        
        // Create room
        $roomSid = $twilioService->createRoom($this->consultation);
        
        if ($roomSid) {
            // Store room SID in consultation
            $this->consultation->update([
                'video_room_sid' => $roomSid,
            ]);
            
            Log::info('Twilio room created and stored', [
                'consultation_id' => $this->consultation->id,
                'room_sid' => $roomSid,
            ]);
        } else {
            Log::error('Failed to create Twilio room', [
                'consultation_id' => $this->consultation->id,
            ]);
        }
    }

    public function getTimeUntilStartProperty()
    {
        if (!$this->consultation->scheduled_at) {
            return null;
        }
        
        return (int) now()->diffInSeconds($this->consultation->scheduled_at, false);
    }

    public function getTimeRemainingProperty()
    {
        if (!$this->consultation->scheduled_at) {
            return null;
        }
        
        $endTime = $this->consultation->scheduled_at->copy()->addMinutes($this->consultation->duration);
        return (int) now()->diffInSeconds($endTime, false);
    }

    public function canAccessVideo(): bool
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
        if (!$this->canAccessVideo()) {
            Log::warning('Unauthorized message send attempt', [
                'consultation_id' => $this->consultation->id,
                'user_id' => auth()->id(),
            ]);
            session()->flash('error', 'Unauthorized access.');
            return;
        }

        // SECURITY: Check if video is active or ended (allow chat after video ends)
        $this->updateVideoStatus();
        
        if ($this->videoStatus === 'waiting') {
            session()->flash('error', 'Video consultation has not started yet.');
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

            // Broadcast message (this will trigger appendNewMessage for all users including sender)
            $broadcasting->broadcastMessage($message);

            // Reset form
            $this->reset(['newMessage', 'attachments']);

            Log::info('Video chat message sent', [
                'message_id' => $message->id,
                'consultation_id' => $this->consultation->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send video chat message', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to send message. Please try again.');
        }
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            $this->attachments = array_values($this->attachments); // Re-index array
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

            Log::info('Video consultation marked as completed', [
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

    // ==========================================
    // RESCHEDULE METHODS
    // ==========================================

    public function openRescheduleModal()
    {
        if (!$this->consultation->canBeRescheduled()) {
            session()->flash('error', 'This consultation cannot be rescheduled at this time.');
            return;
        }

        $this->showRescheduleModal = true;
        $this->selectedDate = null;
        $this->availableSlots = [];
        $this->selectedSlot = null;
        $this->rescheduleReason = '';
    }

    public function updatedSelectedDate($value)
    {
        if (!$value) {
            $this->availableSlots = [];
            return;
        }

        $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
        
        $this->availableSlots = $rescheduleService->getAvailableTimeSlots(
            $this->consultation->lawyer,
            \Carbon\Carbon::parse($value),
            $this->consultation->duration
        );

        $this->selectedSlot = null;
    }

    public function requestReschedule()
    {
        $this->validate([
            'selectedDate' => 'required|date|after:today',
            'selectedSlot' => 'required',
            'rescheduleReason' => 'required|string|min:10|max:500',
        ], [
            'selectedDate.required' => 'Please select a date.',
            'selectedDate.after' => 'Date must be in the future.',
            'selectedSlot.required' => 'Please select a time slot.',
            'rescheduleReason.required' => 'Please provide a reason for rescheduling.',
            'rescheduleReason.min' => 'Reason must be at least 10 characters.',
        ]);

        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $proposedSchedule = \Carbon\Carbon::parse($this->selectedDate . ' ' . $this->selectedSlot);
            
            $result = $rescheduleService->requestReschedule(
                $this->consultation,
                auth()->user(),
                $proposedSchedule,
                $this->rescheduleReason
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->showRescheduleModal = false;
                $this->consultation->refresh();
                $this->updateVideoStatus();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule request failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to request reschedule. Please try again.');
        }
    }

    public function approveReschedule()
    {
        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->approveReschedule(
                $this->consultation,
                auth()->user()
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->consultation->refresh();
                $this->updateVideoStatus();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule approval failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to approve reschedule. Please try again.');
        }
    }

    public function openDeclineModal()
    {
        $this->showDeclineModal = true;
        $this->declineReason = '';
    }

    public function declineReschedule()
    {
        $this->validate([
            'declineReason' => 'required|string|min:10|max:500',
        ], [
            'declineReason.required' => 'Please provide a reason for declining.',
            'declineReason.min' => 'Reason must be at least 10 characters.',
        ]);

        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->declineReschedule(
                $this->consultation,
                auth()->user(),
                $this->declineReason
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->showDeclineModal = false;
                $this->consultation->refresh();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule decline failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to decline reschedule. Please try again.');
        }
    }

    public function cancelRescheduleRequest()
    {
        try {
            $rescheduleService = app(\App\Services\ConsultationRescheduleService::class);
            
            $result = $rescheduleService->cancelRescheduleRequest(
                $this->consultation,
                auth()->user()
            );

            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->consultation->refresh();
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Reschedule cancellation failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to cancel reschedule request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.consultation-video-simple')
            ->layout('layouts.video-consultation', [
                'title' => 'Video Consultation',
            ]);
    }
}
