<?php

namespace App\Livewire;

use App\Models\Consultation;
use App\Models\ConsultationDocument;
use App\Models\LawyerProfile;
use App\Services\FileUploadService;
use App\Services\AvailabilityService;
use App\Services\DeadlineCalculationService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;

class BookConsultation extends Component
{
    use WithFileUploads;

    public LawyerProfile $lawyer;
    
    #[Url]
    public $parent = null; // Parent consultation ID from URL
    
    #[Url]
    public $type = null; // Service type from URL
    
    public $parentConsultation = null; // Parent case details
    public $step = 1; // 1: Select service, 2: Confirm & Request
    
    // Service selection
    public $serviceType = ''; // chat, video, document_review
    public $duration = ''; // 15, 30, 60
    public $title = ''; // Consultation title/subject
    public $scheduledDate = '';
    public $scheduledTime = '';
    public $clientNotes = '';
    
    // Document review - now supports multiple documents
    public $documents = [];
    
    // Supporting documents for chat/video (multiple files)
    public $supportingDocuments = [];
    
    // Calculated
    public $rate = 0;
    public $platformFee = 0;
    public $totalAmount = 0;
    
    // Available time slots
    public $availableSlots = [];

    public function mount(LawyerProfile $lawyer)
    {
        // Prevent lawyers from booking consultations
        if (auth()->check() && auth()->user()->role === 'lawyer') {
            session()->flash('error', 'Lawyers cannot book consultations.');
            return redirect()->route('lawyer.dashboard');
        }
        
        $this->lawyer = $lawyer;
        
        // Load parent consultation if provided
        if ($this->parent) {
            $this->parentConsultation = Consultation::with(['client', 'lawyer'])->find($this->parent);
            
            if ($this->parentConsultation) {
                // Pre-fill title
                $this->title = 'Additional Service: ' . $this->parentConsultation->title;
            }
        }
        
        // Pre-select service type if provided
        if ($this->type && in_array($this->type, ['chat', 'video', 'document_review'])) {
            $this->serviceType = $this->type;
            $this->calculateTotal();
        }
    }

    public function updatedDocuments()
    {
        // Validate multiple documents for document review
        $this->validate([
            'documents.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);
    }
    
    public function updatedSupportingDocuments()
    {
        // Validate multiple supporting documents for chat/video
        $this->validate([
            'supportingDocuments.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);
    }
    
    public function removeDocument($index)
    {
        array_splice($this->documents, $index, 1);
    }
    
    public function removeSupportingDocument($index)
    {
        array_splice($this->supportingDocuments, $index, 1);
    }

    public function updatedServiceType()
    {
        $this->duration = '';
        $this->calculateTotal();
    }

    public function updatedDuration()
    {
        $this->calculateTotal();
    }
    
    public function updatedScheduledDate(AvailabilityService $availabilityService)
    {
        if (!$this->scheduledDate || !$this->duration) {
            $this->availableSlots = [];
            return;
        }
        
        try {
            $date = Carbon::parse($this->scheduledDate);
            $this->availableSlots = $availabilityService->getAvailableSlots(
                $this->lawyer, 
                $date, 
                (int) $this->duration
            );
            
            // Reset selected time if it's no longer available
            if ($this->scheduledTime && !collect($this->availableSlots)->contains('time', $this->scheduledTime)) {
                $this->scheduledTime = '';
            }
        } catch (\Exception $e) {
            $this->availableSlots = [];
        }
    }

    public function calculateTotal()
    {
        if (!$this->serviceType || ($this->serviceType !== 'document_review' && !$this->duration)) {
            $this->rate = 0;
            $this->totalAmount = 0;
            return;
        }

        // Get rate based on service type and duration
        if ($this->serviceType === 'chat') {
            $this->rate = match($this->duration) {
                '15' => $this->lawyer->chat_rate_15min,
                '30' => $this->lawyer->chat_rate_30min,
                '60' => $this->lawyer->chat_rate_60min,
                default => 0,
            };
        } elseif ($this->serviceType === 'video') {
            $this->rate = match($this->duration) {
                '15' => $this->lawyer->video_rate_15min,
                '30' => $this->lawyer->video_rate_30min,
                '60' => $this->lawyer->video_rate_60min,
                default => 0,
            };
        } elseif ($this->serviceType === 'document_review') {
            // For document review, show minimum price but actual price will be quoted by lawyer
            $this->rate = $this->lawyer->document_review_min_price;
        }

        // Calculate platform fee (10%) only for fixed-price services - REMOVED
        if ($this->serviceType !== 'document_review') {
            $this->platformFee = 0;
            $this->totalAmount = $this->rate;
        } else {
            // For document review, don't calculate total yet (will be quoted)
            $this->platformFee = 0;
            $this->totalAmount = 0;
        }
    }

    public function nextStep(AvailabilityService $availabilityService, DeadlineCalculationService $deadlineService)
    {
        \Log::info('=== NEXT STEP CALLED ===', [
            'step' => $this->step,
            'serviceType' => $this->serviceType,
            'scheduledDate' => $this->scheduledDate,
            'scheduledTime' => $this->scheduledTime,
            'duration' => $this->duration,
        ]);
        
        if ($this->step === 1) {
            \Log::info('Starting step 1 validation');
            
            try {
                $rules = [
                    'serviceType' => 'required|in:chat,video,document_review',
                    'title' => 'required|string|min:5|max:100',
                    'clientNotes' => 'required|string|min:20|max:500',
                ];
                
                // Add rules based on service type
                if ($this->serviceType !== 'document_review') {
                    $rules['duration'] = 'required|in:15,30,60';
                    $rules['scheduledDate'] = 'required|date|after:today';
                    $rules['scheduledTime'] = 'required';
                    $rules['supportingDocuments.*'] = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx';
                } else {
                    $rules['documents'] = 'required|array|min:1|max:5';
                    $rules['documents.*'] = 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx';
                }
                
                $this->validate($rules);
                \Log::info('Validation passed');
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Validation failed', [
                    'errors' => $e->errors(),
                    'data' => [
                        'serviceType' => $this->serviceType,
                        'title' => $this->title,
                        'duration' => $this->duration,
                        'scheduledDate' => $this->scheduledDate,
                        'scheduledTime' => $this->scheduledTime,
                        'clientNotes' => strlen($this->clientNotes ?? ''),
                    ]
                ]);
                throw $e;
            }
            
            // Verify availability and deadline for chat/video consultations
            if ($this->serviceType !== 'document_review') {
                \Log::info('Parsing datetime');
                $dateTime = Carbon::parse($this->scheduledDate . ' ' . $this->scheduledTime);
                \Log::info('DateTime parsed', ['dateTime' => $dateTime->toDateTimeString()]);
                
                // Validate booking time (minimum advance booking)
                \Log::info('Validating booking time');
                $validation = $deadlineService->validateBookingTime($dateTime, $this->serviceType);
                \Log::info('Booking time validation result', $validation);
                
                if (!$validation['valid']) {
                    \Log::warning('Booking time invalid', ['message' => $validation['message']]);
                    session()->flash('error', $validation['message']);
                    return;
                }
                
                // Check availability
                \Log::info('Checking availability', [
                    'lawyer_id' => $this->lawyer->user_id,
                    'dateTime' => $dateTime->toDateTimeString(),
                    'duration' => $this->duration,
                ]);
                
                $isAvailable = $availabilityService->isAvailable($this->lawyer, $dateTime, (int) $this->duration);
                \Log::info('Availability result', ['isAvailable' => $isAvailable]);
                
                if (!$isAvailable) {
                    \Log::warning('Time slot not available');
                    session()->flash('error', 'This time slot is no longer available. Please select another time.');
                    $this->updatedScheduledDate($availabilityService);
                    return;
                }
                
                \Log::info('Availability check passed');
            }

            \Log::info('Calculating total');
            $this->calculateTotal();
            \Log::info('Setting step to 2', ['totalAmount' => $this->totalAmount]);
            $this->step = 2;
            \Log::info('Step set to 2 successfully');
        }
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submitRequest(FileUploadService $fileService, DeadlineCalculationService $deadlineService)
    {
        \Log::info('=== SUBMIT REQUEST CALLED ===', [
            'user_id' => auth()->id(),
            'lawyer_id' => $this->lawyer->user_id,
            'serviceType' => $this->serviceType,
            'title' => $this->title,
            'scheduledDate' => $this->scheduledDate,
            'scheduledTime' => $this->scheduledTime,
        ]);
        
        // Build validation rules based on service type
        $rules = [
            'serviceType' => 'required|in:chat,video,document_review',
            'title' => 'required|string|min:5|max:100',
            'clientNotes' => 'required|string|min:20|max:500',
        ];
        
        // Add rules for chat/video consultations
        if ($this->serviceType !== 'document_review') {
            $rules['duration'] = 'required|in:15,30,60';
            $rules['scheduledDate'] = 'required|date|after:today';
            $rules['scheduledTime'] = 'required';
            $rules['supportingDocuments.*'] = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx';
        }
        
        // Add rules for document review
        if ($this->serviceType === 'document_review') {
            $rules['documents'] = 'required|array|min:1|max:5';
            $rules['documents.*'] = 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx';
        }
        
        \Log::info('Validation rules', ['rules' => array_keys($rules)]);
        
        $this->validate($rules);
        \Log::info('Validation rules', ['rules' => array_keys($rules)]);
        
        $this->validate($rules);
        
        \Log::info('Validation passed');

        try {
            \Log::info('Creating consultation', [
                'client_id' => auth()->id(),
                'lawyer_id' => $this->lawyer->user_id,
                'consultation_type' => $this->serviceType,
                'title' => $this->title,
                'duration' => $this->duration,
                'rate' => $this->rate,
                'scheduled_at' => $this->serviceType !== 'document_review' 
                    ? $this->scheduledDate . ' ' . $this->scheduledTime 
                    : null,
            ]);

            // Create consultation request
            $consultation = Consultation::create([
                'client_id' => auth()->id(),
                'lawyer_id' => $this->lawyer->user_id,
                'parent_consultation_id' => $this->parent,
                'consultation_type' => $this->serviceType,
                'title' => $this->title,
                'duration' => $this->serviceType !== 'document_review' ? $this->duration : null,
                'rate' => $this->serviceType !== 'document_review' ? $this->rate : $this->lawyer->document_review_min_price,
                'platform_fee' => 0,
                'total_amount' => $this->serviceType !== 'document_review' ? $this->totalAmount : $this->lawyer->document_review_min_price,
                'status' => $this->lawyer->auto_accept_bookings ? 'payment_pending' : 'pending',
                'scheduled_at' => $this->serviceType !== 'document_review' 
                    ? $this->scheduledDate . ' ' . $this->scheduledTime 
                    : null,
                'accepted_at' => $this->lawyer->auto_accept_bookings ? now() : null,
                'client_notes' => $this->clientNotes,
                'document_path' => null,
                // For auto-accepted document reviews, set 7 days turnaround
                'estimated_turnaround_days' => ($this->lawyer->auto_accept_bookings && $this->serviceType === 'document_review') ? 7 : null,
            ]);
            
            \Log::info('Consultation created', ['id' => $consultation->id, 'status' => $consultation->status]);
            
            // Upload and save documents for document review
            if ($this->serviceType === 'document_review' && !empty($this->documents)) {
                \Log::info('Uploading documents for document review', ['count' => count($this->documents)]);
                
                foreach ($this->documents as $document) {
                    $fileData = $fileService->uploadPrivate($document, 'consultation-documents');
                    
                    ConsultationDocument::create([
                        'consultation_id' => $consultation->id,
                        'uploaded_by' => auth()->id(),
                        'original_filename' => $fileData['original_name'],
                        'stored_filename' => $fileData['encrypted_name'],
                        'file_path' => $fileData['path'],
                        'file_size' => $fileData['size'],
                        'mime_type' => $fileData['mime_type'],
                        'uploaded_at' => now(),
                    ]);
                }
                
                \Log::info('All documents saved to consultation_documents table');
            }
            
            // Upload and save supporting documents for chat/video
            if (in_array($this->serviceType, ['chat', 'video']) && !empty($this->supportingDocuments)) {
                \Log::info('Uploading supporting documents', ['count' => count($this->supportingDocuments)]);
                
                foreach ($this->supportingDocuments as $document) {
                    $fileData = $fileService->uploadPrivate($document, 'consultation-documents');
                    
                    ConsultationDocument::create([
                        'consultation_id' => $consultation->id,
                        'uploaded_by' => auth()->id(),
                        'original_filename' => $fileData['original_name'],
                        'stored_filename' => $fileData['encrypted_name'],
                        'file_path' => $fileData['path'],
                        'file_size' => $fileData['size'],
                        'mime_type' => $fileData['mime_type'],
                        'uploaded_at' => now(),
                    ]);
                }
                
                \Log::info('All supporting documents saved');
            }

            // Calculate and set deadlines
            if ($consultation->status === 'pending') {
                // Lawyer response deadline
                $consultation->lawyer_response_deadline = $deadlineService->calculateLawyerResponseDeadline($consultation);
            } elseif ($consultation->status === 'payment_pending') {
                // Payment deadline (auto-accepted)
                $consultation->payment_deadline_calculated = $deadlineService->calculatePaymentDeadline($consultation);
                // Keep old payment_deadline for backward compatibility
                $consultation->payment_deadline = $consultation->payment_deadline_calculated;
            }
            
            $consultation->save();

            // Send notification to lawyer (only if not auto-accept)
            if (!$this->lawyer->auto_accept_bookings) {
                $this->lawyer->user->notify(new \App\Notifications\ConsultationRequestReceived($consultation));
            }

            // If auto-accept, redirect to payment
            if ($this->lawyer->auto_accept_bookings) {
                session()->flash('success', 'Booking accepted! Please complete payment.');
                return redirect()->route('payment.checkout', $consultation);
            }

            // For manual accept, show pending message
            if ($this->serviceType === 'document_review') {
                session()->flash('success', 'Document review request sent! The lawyer will provide a quote shortly.');
            } else {
                session()->flash('success', 'Consultation request sent! You\'ll be notified when the lawyer responds.');
            }
            return redirect()->route('client.consultations');

        } catch (\Exception $e) {
            \Log::error('Consultation booking failed', [
                'user_id' => auth()->id(),
                'lawyer_id' => $this->lawyer->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.book-consultation')
            ->layout('layouts.dashboard', ['title' => 'Book Consultation']);
    }
}
