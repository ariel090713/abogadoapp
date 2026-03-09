<div>
<div class="w-full h-screen flex flex-col" style="height: 100vh; overflow: hidden;">
    
    <!-- Pending Reschedule Banner (Fixed at top) -->
    @if($consultation->isReschedulePending())
        <div class="flex-shrink-0">
            <x-pending-reschedule-banner :consultation="$consultation" />
        </div>
    @endif
    
    <!-- Header -->
    <div class="flex-shrink-0 bg-blue-900 border-b border-blue-950 px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->isLawyer() ? route('lawyer.consultation.details', $consultation->id) : route('client.consultation.details', $consultation->id) }}" 
                   class="text-white hover:text-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h3 class="font-semibold text-white">{{ $otherUser->name }}</h3>
                    <p class="text-sm text-blue-100">Video Consultation</p>
                </div>
            </div>
            
            @if($videoStatus === 'active')
            <div class="flex items-center gap-2 text-xl font-bold text-white bg-red-700 px-4 py-2.5 rounded-lg shadow-md border border-red-800">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="time-remaining-display"></span>
            </div>
            @endif
        </div>
    </div>

    <!-- Video Area + Chat Side Panel -->
    <div class="flex-1 flex flex-row min-h-0" style="height: calc(100vh - 64px);">
        
        <!-- Video Container (Main Area) -->
        <div class="flex-1 bg-gray-900 relative" style="min-height: 0;">
                
                @if($videoStatus === 'waiting')
                <!-- Waiting State -->
                <div class="flex items-center justify-center h-full p-6">
                    <div class="text-center max-w-full">
                        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Video Not Yet Available</h3>
                                <p class="text-gray-600 mb-4">Your consultation is scheduled for:</p>
                                <p class="text-lg font-semibold text-blue-600">
                                    {{ $consultation->scheduled_at->format('l, F j, Y') }}<br>
                                    {{ $consultation->scheduled_at->format('g:i A') }}
                                </p>
                            </div>

                            <div id="countdown-timer" class="text-center py-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-2">Starting in:</p>
                                <p class="text-3xl font-bold text-gray-900" id="countdown-display">--:--:--</p>
                            </div>

                            <!-- Tips and Reminders -->
                            <div class="max-w-5xl mx-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-left">
                                <!-- Preparation Tips -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-green-900 mb-2">Preparation Tips</h4>
                                            <ul class="text-sm text-green-800 space-y-1">
                                                <li>• Test your camera and microphone</li>
                                                <li>• Find a quiet, well-lit location</li>
                                                <li>• Prepare your questions in advance</li>
                                                <li>• Have relevant documents ready</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Important Reminders -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-blue-900 mb-2">Important Reminders</h4>
                                            <ul class="text-sm text-blue-800 space-y-1">
                                                <li>• Video will be available at scheduled time</li>
                                                <li>• Duration: {{ $consultation->duration }} minutes</li>
                                                <li>• Use chat panel for messages and files</li>
                                                <li>• All communications are confidential</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->isClient())
                                <!-- What to Expect (Client Only) -->
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-purple-900 mb-2">What to Expect</h4>
                                            <ul class="text-sm text-purple-800 space-y-1">
                                                <li>• Professional legal advice from your lawyer</li>
                                                <li>• Opportunity to ask questions and clarify</li>
                                                <li>• Take notes during the consultation</li>
                                                <li>• Request clarification if needed</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <!-- What to Expect (Lawyer Only) -->
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-purple-900 mb-2">What to Expect</h4>
                                            <ul class="text-sm text-purple-800 space-y-1">
                                                <li>• Provide professional legal consultation</li>
                                                <li>• Listen to client's concerns and questions</li>
                                                <li>• Offer clear legal advice and recommendations</li>
                                                <li>• Maintain professional conduct throughout</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($videoStatus === 'active')
                <!-- Active Video State -->
                <div id="twilio-video-container" class="w-full h-full relative" wire:ignore>
                    <!-- Remote participant video (full screen) -->
                    <div id="remote-media" class="absolute inset-0 w-full h-full bg-gray-900"></div>
                    
                    <!-- Remote video disabled placeholder -->
                    <div id="remote-video-placeholder" class="hidden absolute inset-0 w-full h-full bg-gray-900 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <p class="text-white text-lg font-medium">Camera is off</p>
                            <p class="text-gray-400 text-sm mt-2">{{ $otherUser->name }} has disabled their camera</p>
                        </div>
                    </div>
                    
                    <!-- Local participant video (small overlay) -->
                    <div id="local-media" class="absolute bottom-4 right-4 w-48 h-36 rounded-xl overflow-hidden shadow-2xl bg-gray-800 border-2 border-white"></div>
                    
                    <!-- Video Controls -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-3 bg-black bg-opacity-70 px-5 py-3 rounded-full backdrop-blur-sm">
                        <button id="toggle-video" class="w-12 h-12 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-white transition">
                            <svg id="video-on-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <svg id="video-off-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </button>
                        
                        <button id="toggle-audio" class="w-12 h-12 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-white transition">
                            <svg id="audio-on-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                            <svg id="audio-off-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                            </svg>
                        </button>
                        
                        <button 
                            @click="$dispatch('toggle-chat')"
                            class="w-12 h-12 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-white transition lg:hidden"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                @else
                <!-- Ended State -->
                <div class="flex items-center justify-center h-full p-6">
                    <div class="text-center max-w-md">
                        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Video Consultation Ended</h3>
                                <p class="text-gray-600 mb-4">The scheduled time for this consultation has ended.</p>
                                
                                @if($consultation->status !== 'completed' && auth()->user()->isLawyer())
                                <p class="text-sm text-gray-500">Please mark this consultation as completed to finalize it.</p>
                                @endif
                            </div>

                            <div class="space-y-3">
                                <a href="{{ auth()->user()->isLawyer() ? route('lawyer.consultation.details', $consultation->id) : route('client.consultation.details', $consultation->id) }}" 
                                   class="inline-block w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    View Consultation Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Chat Side Panel (Desktop) / Full Screen Popup (Mobile) -->
            <div 
                x-data="{ chatOpen: false }" 
                @toggle-chat.window="chatOpen = true"
                id="chat-side-panel" 
                class="lg:flex lg:w-96 flex-shrink-0 bg-white border-l border-gray-200 flex-col fixed lg:relative inset-0 lg:inset-auto z-50 lg:z-auto"
                :class="{ 'hidden': !chatOpen && window.innerWidth < 1024, 'flex': chatOpen || window.innerWidth >= 1024 }"
                wire:ignore.self
            >
                <!-- Close button for mobile -->
                <div class="lg:hidden flex items-center justify-between p-4 border-b border-gray-200 bg-white">
                    <h3 class="font-semibold text-gray-900">Chat</h3>
                    <button @click="chatOpen = false" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                @include('livewire.partials.video-chat-panel')
            </div>

        </div>



    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <script src="//media.twiliocdn.com/sdk/js/video/releases/2.27.0/twilio-video.min.js"></script>
    <script>
        let room = null;
        let localVideoTrack = null;
        let localAudioTrack = null;
        let isVideoEnabled = true;
        let isAudioEnabled = true;

        document.addEventListener('DOMContentLoaded', function() {
            const videoStatus = '{{ $videoStatus }}';
            
            console.log('Video consultation loaded', {
                status: videoStatus,
                id: '{{ $consultation->id }}',
                twilioLoaded: typeof Twilio !== 'undefined'
            });
            
            // Check if Twilio SDK is loaded
            if (typeof Twilio === 'undefined') {
                console.error('Twilio SDK not loaded!');
                alert('Video SDK failed to load. Please refresh the page.');
                return;
            }

            if (videoStatus === 'waiting') {
                startCountdown();
                
                // Fallback: Check every 10 seconds if time has passed
                setInterval(() => {
                    const timeUntilStart = {{ $this->timeUntilStart ?? 0 }};
                    if (timeUntilStart <= 0) {
                        location.reload();
                    }
                }, 10000);
            } else if (videoStatus === 'active') {
                initializeTwilioVideo();
                startTimeRemaining();
                
                // Initialize chat - scroll to bottom
                setTimeout(() => {
                    if (typeof scrollToBottom === 'function') {
                        scrollToBottom();
                    }
                }, 800);
            }

            
            // Initialize Pusher if not already initialized
            if (typeof window.pusher === 'undefined') {
                window.pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    encrypted: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }
                });
            }
            
            // Subscribe to Pusher channel for real-time messages
            const consultationId = {{ $consultation->id }};
            const channel = window.pusher.subscribe('private-consultation.' + consultationId);
            
            channel.bind('message.sent', function(data) {
                console.log('New message received via Pusher:', data);
                console.log('Dispatching appendNewMessage with ID:', data.id);
                // Append new message via Livewire (don't refresh entire list)
                Livewire.dispatch('appendNewMessage', { messageId: data.id });
            });
            
            console.log('Subscribed to consultation channel:', 'private-consultation.' + consultationId);
        });

        function startCountdown() {
            const timeUntilStart = {{ $this->timeUntilStart ?? 0 }};
            
            console.log('Time until start:', timeUntilStart);
            
            if (timeUntilStart <= 0) {
                location.reload();
                return;
            }

            let remaining = timeUntilStart;
            const display = document.getElementById('countdown-display');
            
            if (!display) {
                console.error('Countdown display element not found');
                return;
            }
            
            const interval = setInterval(() => {
                remaining--;
                
                const hours = Math.floor(remaining / 3600);
                const minutes = Math.floor((remaining % 3600) / 60);
                const seconds = remaining % 60;
                
                display.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (remaining <= 0) {
                    clearInterval(interval);
                    location.reload();
                }
            }, 1000);
        }

        function startTimeRemaining() {
            const timeRemaining = {{ $this->timeRemaining ?? 0 }};
            
            if (timeRemaining <= 0) {
                endVideoConsultation();
                return;
            }

            let remaining = timeRemaining;
            const display = document.getElementById('time-remaining-display');
            
            const interval = setInterval(() => {
                remaining = Math.floor(remaining - 1);
                
                const hours = Math.floor(remaining / 3600);
                const minutes = Math.floor((remaining % 3600) / 60);
                const seconds = Math.floor(remaining % 60);
                
                if (hours > 0) {
                    display.textContent = `${hours}h ${minutes}m ${seconds}s`;
                } else if (minutes > 0) {
                    display.textContent = `${minutes}m ${seconds}s`;
                } else {
                    display.textContent = `${seconds}s`;
                }
                
                if (remaining <= 0) {
                    clearInterval(interval);
                    endVideoConsultation();
                }
            }, 1000);
        }

        async function initializeTwilioVideo() {
            const token = '{{ $accessToken }}';
            const roomName = 'consultation-{{ $consultation->id }}';
            
            console.log('Initializing Twilio Video...', {
                token: token ? 'Token exists' : 'No token',
                roomName: roomName
            });
            
            if (!token) {
                console.error('No access token available');
                alert('Failed to initialize video: No access token');
                return;
            }
            
            try {
                console.log('Connecting to Twilio room...');
                
                // Connect to Twilio Video room
                room = await Twilio.Video.connect(token, {
                    name: roomName,
                    audio: true,
                    video: { width: 640, height: 480 }
                });

                console.log('Connected to room:', room.name);

                // Attach local tracks
                room.localParticipant.tracks.forEach(publication => {
                    if (publication.track) {
                        console.log('Attaching local track:', publication.track.kind);
                        attachTrack(publication.track, 'local-media');
                        
                        if (publication.track.kind === 'video') {
                            localVideoTrack = publication.track;
                        } else if (publication.track.kind === 'audio') {
                            localAudioTrack = publication.track;
                        }
                    }
                });

                // Attach remote participant tracks
                room.participants.forEach(participant => {
                    console.log('Remote participant found:', participant.identity);
                    
                    participant.tracks.forEach(publication => {
                        if (publication.isSubscribed) {
                            console.log('Attaching remote track:', publication.track.kind);
                            attachTrack(publication.track, 'remote-media');
                        }
                    });

                    participant.on('trackSubscribed', track => {
                        console.log('Remote track subscribed:', track.kind);
                        attachTrack(track, 'remote-media');
                    });
                    
                    participant.on('trackUnsubscribed', track => {
                        console.log('Remote track unsubscribed:', track.kind);
                        detachTrack(track, 'remote-media');
                    });
                    
                    participant.on('trackDisabled', track => {
                        console.log('Remote track disabled:', track.kind);
                        if (track.kind === 'video') {
                            showRemoteVideoPlaceholder();
                        }
                    });
                    
                    participant.on('trackEnabled', track => {
                        console.log('Remote track enabled:', track.kind);
                        if (track.kind === 'video') {
                            hideRemoteVideoPlaceholder();
                        }
                    });
                });

                // Handle new participants
                room.on('participantConnected', participant => {
                    console.log('Participant connected:', participant.identity);
                    
                    participant.tracks.forEach(publication => {
                        if (publication.isSubscribed) {
                            attachTrack(publication.track, 'remote-media');
                        }
                    });

                    participant.on('trackSubscribed', track => {
                        console.log('New participant track subscribed:', track.kind);
                        attachTrack(track, 'remote-media');
                    });
                    
                    participant.on('trackUnsubscribed', track => {
                        console.log('New participant track unsubscribed:', track.kind);
                        detachTrack(track, 'remote-media');
                    });
                    
                    participant.on('trackDisabled', track => {
                        console.log('New participant track disabled:', track.kind);
                        if (track.kind === 'video') {
                            showRemoteVideoPlaceholder();
                        }
                    });
                    
                    participant.on('trackEnabled', track => {
                        console.log('New participant track enabled:', track.kind);
                        if (track.kind === 'video') {
                            hideRemoteVideoPlaceholder();
                        }
                    });
                });

                // Setup video controls
                setupVideoControls();

            } catch (error) {
                console.error('Failed to connect to video room:', error);
                alert('Failed to connect to video: ' + error.message + '\n\nPlease check:\n1. Camera/microphone permissions\n2. Internet connection\n3. Browser compatibility');
            }
        }

        function attachTrack(track, containerId) {
            const container = document.getElementById(containerId);
            if (container) {
                console.log('Attaching track to', containerId, track.kind);
                const element = track.attach();
                
                // Force video elements to fill container
                if (track.kind === 'video') {
                    element.style.width = '100%';
                    element.style.height = '100%';
                    element.style.objectFit = 'cover';
                    
                    // Hide placeholder when video is attached
                    if (containerId === 'remote-media') {
                        hideRemoteVideoPlaceholder();
                    }
                }
                
                container.appendChild(element);
                console.log('Track attached successfully');
            } else {
                console.error('Container not found:', containerId);
            }
        }
        
        function detachTrack(track, containerId) {
            const container = document.getElementById(containerId);
            if (container) {
                console.log('Detaching track from', containerId, track.kind);
                track.detach().forEach(element => element.remove());
                
                // Show placeholder when remote video is detached
                if (track.kind === 'video' && containerId === 'remote-media') {
                    showRemoteVideoPlaceholder();
                }
            }
        }
        
        function showRemoteVideoPlaceholder() {
            const placeholder = document.getElementById('remote-video-placeholder');
            const remoteMedia = document.getElementById('remote-media');
            if (placeholder && remoteMedia) {
                placeholder.classList.remove('hidden');
                remoteMedia.classList.add('hidden');
            }
        }
        
        function hideRemoteVideoPlaceholder() {
            const placeholder = document.getElementById('remote-video-placeholder');
            const remoteMedia = document.getElementById('remote-media');
            if (placeholder && remoteMedia) {
                placeholder.classList.add('hidden');
                remoteMedia.classList.remove('hidden');
            }
        }

        function setupVideoControls() {
            // Toggle video
            document.getElementById('toggle-video').addEventListener('click', function() {
                if (localVideoTrack) {
                    const videoOnIcon = document.getElementById('video-on-icon');
                    const videoOffIcon = document.getElementById('video-off-icon');
                    
                    if (isVideoEnabled) {
                        localVideoTrack.disable();
                        this.classList.add('bg-red-600');
                        this.classList.remove('bg-gray-700');
                        videoOnIcon.classList.add('hidden');
                        videoOffIcon.classList.remove('hidden');
                    } else {
                        localVideoTrack.enable();
                        this.classList.remove('bg-red-600');
                        this.classList.add('bg-gray-700');
                        videoOnIcon.classList.remove('hidden');
                        videoOffIcon.classList.add('hidden');
                    }
                    isVideoEnabled = !isVideoEnabled;
                }
            });

            // Toggle audio
            document.getElementById('toggle-audio').addEventListener('click', function() {
                if (localAudioTrack) {
                    const audioOnIcon = document.getElementById('audio-on-icon');
                    const audioOffIcon = document.getElementById('audio-off-icon');
                    
                    if (isAudioEnabled) {
                        localAudioTrack.disable();
                        this.classList.add('bg-red-600');
                        this.classList.remove('bg-gray-700');
                        audioOnIcon.classList.add('hidden');
                        audioOffIcon.classList.remove('hidden');
                    } else {
                        localAudioTrack.enable();
                        this.classList.remove('bg-red-600');
                        this.classList.add('bg-gray-700');
                        audioOnIcon.classList.remove('hidden');
                        audioOffIcon.classList.add('hidden');
                    }
                    isAudioEnabled = !isAudioEnabled;
                }
            });
        }

        function endVideoConsultation() {
            if (room) {
                room.disconnect();
            }
            location.reload();
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (room) {
                room.disconnect();
            }
        });
    </script>

</div>
</div>
