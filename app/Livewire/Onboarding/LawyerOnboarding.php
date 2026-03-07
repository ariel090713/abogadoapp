<?php

namespace App\Livewire\Onboarding;

use App\Models\LawyerProfile;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class LawyerOnboarding extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $totalSteps = 6; // Added availability and confirmation steps
    
    // Step 1: Personal Info
    public $phone = '';
    public $province = '';
    public $city = '';
    
    // Step 2: Professional Credentials
    public $ibpNumber = '';
    public $ibpCard;
    public $yearsExperience = '';
    public $lawSchool = '';
    public $graduationYear = '';
    
    // Step 3: Practice Areas & Bio
    public $specializations = [];
    public $bio = '';
    public $languages = [];
    
    // Step 4: Service Pricing
    public $offersChat = false;
    public $chatRate15min = '';
    public $chatRate30min = '';
    public $chatRate60min = '';
    
    public $offersVideo = false;
    public $videoRate15min = '';
    public $videoRate30min = '';
    public $videoRate60min = '';
    
    public $offersDocumentReview = false;
    public $documentReviewMinPrice = '';
    
    // Step 5: Availability Schedule
    public $schedule = [
        'monday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        'tuesday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        'wednesday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        'thursday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        'friday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        'saturday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        'sunday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
    ];
    
    protected function rules()
    {
        return [
            'phone' => 'required|regex:/^9[0-9]{9}$/',
            'province' => 'required|string',
            'city' => 'required|string',
            'ibpNumber' => 'required|string|min:4|max:20',
            'ibpCard' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'yearsExperience' => 'required|integer|min:0|max:50',
            'lawSchool' => 'required|string|max:255',
            'graduationYear' => 'required|integer|min:1950|max:' . date('Y'),
            'specializations' => 'required|array|min:1',
            'bio' => 'required|string|min:100|max:1000',
            // Service offerings
            'offersChat' => 'boolean',
            'offersVideo' => 'boolean',
            'offersDocumentReview' => 'boolean',
            // Chat rates (required if offering chat)
            'chatRate15min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:10000',
            'chatRate30min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:20000',
            'chatRate60min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:40000',
            // Video rates (required if offering video)
            'videoRate15min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:10000',
            'videoRate30min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:20000',
            'videoRate60min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:40000',
            // Document review (required if offering document review)
            'documentReviewMinPrice' => 'required_if:offersDocumentReview,true|nullable|numeric|min:100|max:50000',
        ];
    }

    protected function messages()
    {
        return [
            'ibpCard.file' => 'The uploaded file must be a valid file.',
            'ibpCard.max' => 'The IBP card file must not exceed 10MB.',
            'ibpCard.mimes' => 'The IBP card must be a PDF, JPG, JPEG, or PNG file.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be a valid Philippine mobile number (e.g., 9171234567).',
            'province.required' => 'Province is required.',
            'city.required' => 'City is required.',
            'ibpNumber.required' => 'IBP number is required.',
            'ibpNumber.min' => 'IBP number must be at least 4 characters.',
            'ibpNumber.max' => 'IBP number must not exceed 20 characters.',
            'yearsExperience.required' => 'Years of experience is required.',
            'yearsExperience.min' => 'Years of experience cannot be negative.',
            'yearsExperience.max' => 'Years of experience seems too high. Please verify.',
            'lawSchool.required' => 'Law school is required.',
            'graduationYear.required' => 'Graduation year is required.',
            'graduationYear.min' => 'Graduation year must be 1950 or later.',
            'graduationYear.max' => 'Graduation year cannot be in the future.',
            'specializations.required' => 'At least one specialization is required.',
            'specializations.min' => 'Please select at least one specialization.',
            'bio.required' => 'Professional bio is required.',
            'bio.min' => 'Bio must be at least 100 characters.',
            'bio.max' => 'Bio must not exceed 1000 characters.',
            'chatRate15min.required_if' => '15-minute chat rate is required when offering chat service.',
            'chatRate30min.required_if' => '30-minute chat rate is required when offering chat service.',
            'chatRate60min.required_if' => '60-minute chat rate is required when offering chat service.',
            'videoRate15min.required_if' => '15-minute video rate is required when offering video service.',
            'videoRate30min.required_if' => '30-minute video rate is required when offering video service.',
            'videoRate60min.required_if' => '60-minute video rate is required when offering video service.',
            'documentReviewMinPrice.required_if' => 'Document review minimum price is required when offering document review service.',
        ];
    }

    public function mount()
    {
        $user = auth()->user();
        $this->phone = $user->phone ? str_replace('+63', '', $user->phone) : '';
        $this->province = $user->province ?? '';
        $this->city = $user->city ?? '';
        $this->languages = \App\Helpers\Languages::getDefault();
    }

    public function updatedIbpCard()
    {
        \Log::info('updatedIbpCard method called', [
            'user_id' => auth()->id(),
            'file_exists' => $this->ibpCard ? 'yes' : 'no',
        ]);
        
        // Clear any previous errors when a new file is selected
        $this->resetErrorBag('ibpCard');
        
        if (!$this->ibpCard) {
            \Log::warning('No file in updatedIbpCard', ['user_id' => auth()->id()]);
            return;
        }
        
        try {
            $fileName = $this->ibpCard->getClientOriginalName();
            $fileSize = $this->ibpCard->getSize();
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
            $mimeType = $this->ibpCard->getMimeType();
            
            \Log::info('Starting file validation', [
                'user_id' => auth()->id(),
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_size_mb' => $fileSizeMB,
                'mime_type' => $mimeType,
            ]);
            
            // Check file size first (10MB = 10240 KB)
            if ($fileSize > 10240 * 1024) {
                $errorMsg = "File is too large ({$fileSizeMB}MB). Maximum size is 10MB.";
                \Log::warning('File too large', [
                    'user_id' => auth()->id(),
                    'file_size_mb' => $fileSizeMB,
                ]);
                $this->addError('ibpCard', $errorMsg);
                return;
            }
            
            // Check file type
            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            $extension = strtolower($this->ibpCard->getClientOriginalExtension());
            
            if (!in_array($mimeType, $allowedMimes) || !in_array($extension, $allowedExtensions)) {
                $errorMsg = "Invalid file type. Only PDF, JPG, JPEG, and PNG files are allowed.";
                \Log::warning('Invalid file type', [
                    'user_id' => auth()->id(),
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                ]);
                $this->addError('ibpCard', $errorMsg);
                return;
            }
            
            // Validate the file
            $this->validate([
                'ibpCard' => 'file|max:10240|mimes:pdf,jpg,jpeg,png',
            ]);
            
            \Log::info('IBP card file uploaded and validated successfully', [
                'user_id' => auth()->id(),
                'file_name' => $fileName,
                'file_size_mb' => $fileSizeMB,
                'mime_type' => $mimeType,
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = 'Validation failed: ';
            $errors = $e->validator->errors()->get('ibpCard');
            if (!empty($errors)) {
                $errorMsg .= implode(' ', $errors);
            } else {
                $errorMsg .= 'Please check your file and try again.';
            }
            
            \Log::error('IBP card validation failed', [
                'user_id' => auth()->id(),
                'error' => $errorMsg,
                'validation_errors' => $e->validator->errors()->toArray(),
            ]);
            
            $this->addError('ibpCard', $errorMsg);
            
        } catch (\Exception $e) {
            $errorMsg = 'Upload failed: ' . $e->getMessage();
            
            \Log::error('IBP card upload exception', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $this->addError('ibpCard', $errorMsg);
        }
    }

    public function updatedProvince()
    {
        $this->city = '';
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'phone' => 'required|regex:/^9[0-9]{9}$/',
                'province' => 'required|string',
                'city' => 'required|string',
                'languages' => 'required|array|min:1',
            ]);
        }

        if ($this->step === 2) {
            // Custom validation for file upload
            if (!$this->ibpCard) {
                $this->addError('ibpCard', 'The IBP card field is required.');
                return;
            }

            $this->validate([
                'ibpNumber' => 'required|string|min:4|max:20',
                'yearsExperience' => 'required|integer|min:0|max:50',
                'lawSchool' => 'required|string|max:255',
                'graduationYear' => 'required|integer|min:1950|max:' . date('Y'),
            ]);

            // Validate file after other fields
            $this->validate([
                'ibpCard' => 'file|max:10240|mimes:pdf,jpg,jpeg,png',
            ]);
        }

        if ($this->step === 3) {
            $this->validate([
                'specializations' => 'required|array|min:1',
                'bio' => 'required|string|min:100|max:1000',
            ]);
        }

        if ($this->step === 4) {
            // At least one service must be offered
            if (!$this->offersChat && !$this->offersVideo && !$this->offersDocumentReview) {
                $this->addError('services', 'You must offer at least one service.');
                return;
            }
            
            $this->validate([
                'offersChat' => 'boolean',
                'offersVideo' => 'boolean',
                'offersDocumentReview' => 'boolean',
                'chatRate15min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:10000',
                'chatRate30min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:20000',
                'chatRate60min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:40000',
                'videoRate15min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:10000',
                'videoRate30min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:20000',
                'videoRate60min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:40000',
                'documentReviewMinPrice' => 'required_if:offersDocumentReview,true|nullable|numeric|min:100|max:50000',
            ]);
        }

        if ($this->step === 5) {
            // At least one day must be enabled
            $hasEnabledDay = false;
            foreach ($this->schedule as $day => $times) {
                if ($times['enabled']) {
                    $hasEnabledDay = true;
                    break;
                }
            }
            
            if (!$hasEnabledDay) {
                $this->addError('schedule', 'Please select at least one available day.');
                return;
            }
        }

        $this->step++;
    }

    public function previousStep()
    {
        if ($this->step === 1) {
            // Don't reset role to null, just go back to role selection
            return $this->redirect(route('onboarding.start'));
        }
        $this->step--;
    }

    public function complete(FileUploadService $fileService)
    {
        // Validate that at least one service is offered
        if (!$this->offersChat && !$this->offersVideo && !$this->offersDocumentReview) {
            $this->addError('services', 'You must offer at least one service.');
            return;
        }

        $this->validate([
            'offersChat' => 'boolean',
            'offersVideo' => 'boolean', 
            'offersDocumentReview' => 'boolean',
            'chatRate15min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:10000',
            'chatRate30min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:20000',
            'chatRate60min' => 'required_if:offersChat,true|nullable|numeric|min:100|max:40000',
            'videoRate15min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:10000',
            'videoRate30min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:20000',
            'videoRate60min' => 'required_if:offersVideo,true|nullable|numeric|min:100|max:40000',
            'documentReviewMinPrice' => 'required_if:offersDocumentReview,true|nullable|numeric|min:100|max:50000',
        ]);

        $user = auth()->user();
        
        try {
            \Log::info('Starting lawyer onboarding completion', [
                'user_id' => $user->id,
                'step' => $this->step,
            ]);
            
            // Update user info
            $user->update([
                'phone' => '+63' . $this->phone,
                'province' => $this->province,
                'city' => $this->city,
                'location' => $this->city . ', ' . $this->province,
            ]);

            \Log::info('User info updated, starting IBP card upload', [
                'user_id' => $user->id,
            ]);

            // Upload IBP card to private bucket
            $ibpData = $fileService->uploadPrivate(
                $this->ibpCard,
                'ibp-cards'
            );

            \Log::info('IBP card uploaded successfully', [
                'user_id' => $user->id,
                'file_path' => $ibpData['path'],
            ]);

            // Generate username from name
            $username = \Illuminate\Support\Str::slug($user->name) . '-' . \Illuminate\Support\Str::random(6);

            // Create lawyer profile with new service pricing
            $lawyerProfile = LawyerProfile::create([
                'user_id' => $user->id,
                'ibp_number' => 'IBP-' . $this->ibpNumber,
                'bio' => $this->bio,
                'languages' => $this->languages,
                'years_experience' => $this->yearsExperience,
                'law_school' => $this->lawSchool,
                'graduation_year' => $this->graduationYear,
                'rating' => 0,
                'total_reviews' => 0,
                'total_consultations' => 0,
                'is_verified' => false, // Requires admin approval
                'is_available' => false,
                'username' => $username,
                // Service offerings
                'offers_chat_consultation' => $this->offersChat,
                'offers_video_consultation' => $this->offersVideo,
                'offers_document_review' => $this->offersDocumentReview,
                // Chat rates
                'chat_rate_15min' => $this->offersChat ? $this->chatRate15min : null,
                'chat_rate_30min' => $this->offersChat ? $this->chatRate30min : null,
                'chat_rate_60min' => $this->offersChat ? $this->chatRate60min : null,
                // Video rates
                'video_rate_15min' => $this->offersVideo ? $this->videoRate15min : null,
                'video_rate_30min' => $this->offersVideo ? $this->videoRate30min : null,
                'video_rate_60min' => $this->offersVideo ? $this->videoRate60min : null,
                // Document review
                'document_review_min_price' => $this->offersDocumentReview ? $this->documentReviewMinPrice : null,
                // Default settings
                'auto_accept_bookings' => true,
            ]);

            // Attach specializations
            $lawyerProfile->specializations()->attach($this->specializations);

            // Save availability schedule
            $dayMapping = [
                'sunday' => 0,
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6,
            ];

            foreach ($this->schedule as $day => $times) {
                if ($times['enabled']) {
                    \App\Models\AvailabilitySchedule::create([
                        'lawyer_profile_id' => $lawyerProfile->id,
                        'day_of_week' => $dayMapping[$day],
                        'start_time' => $times['start'],
                        'end_time' => $times['end'],
                        'is_available' => true,
                    ]);
                }
            }

            // Store IBP card path in onboarding data for admin review
            $user->update([
                'onboarding_completed_at' => now(),
                'onboarding_data' => [
                    'ibp_card_path' => $ibpData['path'],
                    'ibp_card_original_name' => $ibpData['original_name'],
                ],
            ]);

            \Log::info('Lawyer onboarding completed successfully', [
                'user_id' => $user->id,
                'lawyer_profile_id' => $lawyerProfile->id,
            ]);

            session()->flash('success', 'Your lawyer profile has been submitted for verification. We\'ll notify you once approved.');
            return redirect()->route('onboarding.success');

        } catch (\Exception $e) {
            \Log::error('Lawyer onboarding failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Something went wrong during submission. Please try again.');
        }
    }

    public function render()
    {
        $allSpecializations = \App\Models\Specialization::where('is_parent', true)
            ->with('children')
            ->orderBy('name')
            ->get();

        $provinces = \App\Helpers\PhilippineLocations::getProvincesList();
        $cities = $this->province ? \App\Helpers\PhilippineLocations::getCitiesByProvince($this->province) : [];
        $availableLanguages = \App\Helpers\Languages::available();

        return view('livewire.onboarding.lawyer-onboarding', [
            'allSpecializations' => $allSpecializations,
            'provinces' => $provinces,
            'cities' => $cities,
            'availableLanguages' => $availableLanguages,
        ])->layout('layouts.guest', [
            'hideNavbar' => true,
            'hideFooter' => true,
        ]);
    }
}
