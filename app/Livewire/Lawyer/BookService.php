<?php

namespace App\Livewire\Lawyer;

use App\Models\Consultation;
use App\Services\AvailabilityService;
use App\Services\DeadlineCalculationService;
use Carbon\Carbon;
use Livewire\Component;

class BookService extends Component
{
    public Consultation $parentCase;
    public $step = 1;
    
    // Service details
    public $serviceType = '';
    public $duration = '';
    public $title = '';
    public $scheduledDate = '';
    public $scheduledTime = '';
    public $notes = '';
    
    // Pricing
    public $pricingType = 'free'; // 'free' or 'quoted'
    public $quotedPrice = 0;
    
    // Calculated
    public $availableSlots = [];

    public function mount(Consultation $consultation)
    {
        // Ensure this is a case (parent consultation)
        if ($consultation->parent_consultation_id !== null) {
            session()->flash('error', 'Cannot add service to a child consultation.');
            return redirect()->route('lawyer.cases');
        }
        
        // Ensure lawyer owns this case
        if ($consultation->lawyer_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized access.');
            return redirect()->route('lawyer.cases');
        }
        
        $this->parentCase = $consultation;
        
        // Pre-fill title
        $this->title = 'Additional Service: ' . $this->parentCase->title;
    }

    public function updatedServiceType()
    {
        $this->duration = '';
        $this->scheduledDate = '';
        $this->scheduledTime = '';
        $this->availableSlots = [];
    }

    public function updatedDuration()
    {
        $this->scheduledTime = '';
        if ($this->scheduledDate) {
            $this->loadAvailableSlots();
        }
    }
    
    public function updatedScheduledDate()
    {
        $this->scheduledTime = '';
        if ($this->duration) {
            $this->loadAvailableSlots();
        }
    }
    
    public function loadAvailableSlots()
    {
        if (!$this->scheduledDate || !$this->duration) {
            $this->availableSlots = [];
            return;
        }
        
        try {
            $availabilityService = app(AvailabilityService::class);
            $date = Carbon::parse($this->scheduledDate);
            $this->availableSlots = $availabilityService->getAvailableSlots(
                auth()->user()->lawyerProfile,
                $date,
                (int) $this->duration
            );
            
            if ($this->scheduledTime && !collect($this->availableSlots)->contains('time', $this->scheduledTime)) {
                $this->scheduledTime = '';
            }
        } catch (\Exception $e) {
            $this->availableSlots = [];
        }
    }

    public function nextStep(DeadlineCalculationService $deadlineService)
    {
        if ($this->step === 1) {
            $rules = [
                'serviceType' => 'required|in:chat,video,document_review',
                'title' => 'required|string|min:5|max:100',
                'notes' => 'required|string|min:20|max:500',
                'pricingType' => 'required|in:free,quoted',
            ];
            
            if ($this->serviceType !== 'document_review') {
                $rules['duration'] = 'required|in:15,30,60';
                $rules['scheduledDate'] = 'required|date|after:today';
                $rules['scheduledTime'] = 'required';
            }
            
            if ($this->pricingType === 'quoted') {
                $rules['quotedPrice'] = 'required|numeric|min:1';
            }
            
            $this->validate($rules);
            
            // Validate booking time for chat/video
            if ($this->serviceType !== 'document_review') {
                $dateTime = Carbon::parse($this->scheduledDate . ' ' . $this->scheduledTime);
                
                $validation = $deadlineService->validateBookingTime($dateTime, $this->serviceType);
                if (!$validation['valid']) {
                    session()->flash('error', $validation['message']);
                    return;
                }
                
                $availabilityService = app(AvailabilityService::class);
                $isAvailable = $availabilityService->isAvailable(
                    auth()->user()->lawyerProfile,
                    $dateTime,
                    (int) $this->duration
                );
                
                if (!$isAvailable) {
                    session()->flash('error', 'This time slot is no longer available.');
                    $this->loadAvailableSlots();
                    return;
                }
            }

            $this->step = 2;
        }
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submitOffer()
    {
        $rules = [
            'serviceType' => 'required|in:chat,video,document_review',
            'title' => 'required|string|min:5|max:100',
            'notes' => 'required|string|min:20|max:500',
            'pricingType' => 'required|in:free,quoted',
        ];
        
        if ($this->serviceType !== 'document_review') {
            $rules['duration'] = 'required|in:15,30,60';
            $rules['scheduledDate'] = 'required|date|after:today';
            $rules['scheduledTime'] = 'required';
        }
        
        if ($this->pricingType === 'quoted') {
            $rules['quotedPrice'] = 'required|numeric|min:1';
        }
        
        $this->validate($rules);

        try {
            $price = $this->pricingType === 'free' ? 0 : $this->quotedPrice;
            
            $consultation = Consultation::create([
                'client_id' => $this->parentCase->client_id,
                'lawyer_id' => auth()->id(),
                'parent_consultation_id' => $this->parentCase->id,
                'initiated_by' => 'lawyer',
                'consultation_type' => $this->serviceType,
                'title' => $this->title,
                'duration' => $this->serviceType !== 'document_review' ? $this->duration : null,
                'rate' => $price,
                'quoted_price' => $price,
                'quote_notes' => $this->notes,
                'total_amount' => $price,
                'status' => 'pending_client_acceptance',
                'scheduled_at' => $this->serviceType !== 'document_review' 
                    ? $this->scheduledDate . ' ' . $this->scheduledTime 
                    : null,
                'quote_provided_at' => now(),
            ]);

            // Send notification to client
            $this->parentCase->client->notify(
                new \App\Notifications\ServiceOfferedByLawyer($consultation)
            );

            session()->flash('success', 'Service offer sent to client successfully!');
            return redirect()->route('lawyer.consultation-thread.details', $this->parentCase->id);

        } catch (\Exception $e) {
            \Log::error('Lawyer service booking failed', [
                'lawyer_id' => auth()->id(),
                'parent_case_id' => $this->parentCase->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.lawyer.book-service')
            ->layout('layouts.dashboard', ['title' => 'Offer Additional Service']);
    }
}
