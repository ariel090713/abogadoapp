<?php

namespace App\Livewire\Lawyer;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileServices extends Component
{
    // Service pricing
    public $chat_rate_15min;
    public $chat_rate_30min;
    public $chat_rate_60min;
    public $video_rate_15min;
    public $video_rate_30min;
    public $video_rate_60min;
    public $document_review_min_price;

    // Service availability
    public $offers_chat_consultation = true;
    public $offers_video_consultation = true;
    public $offers_document_review = true;
    public $auto_accept_bookings = false;
    public $is_available = true;

    public function mount()
    {
        $profile = Auth::user()->lawyerProfile;
        
        if ($profile) {
            $this->chat_rate_15min = $profile->chat_rate_15min;
            $this->chat_rate_30min = $profile->chat_rate_30min;
            $this->chat_rate_60min = $profile->chat_rate_60min;
            $this->video_rate_15min = $profile->video_rate_15min;
            $this->video_rate_30min = $profile->video_rate_30min;
            $this->video_rate_60min = $profile->video_rate_60min;
            $this->document_review_min_price = $profile->document_review_min_price;
            $this->offers_chat_consultation = $profile->offers_chat_consultation;
            $this->offers_video_consultation = $profile->offers_video_consultation;
            $this->offers_document_review = $profile->offers_document_review;
            $this->auto_accept_bookings = $profile->auto_accept_bookings;
            $this->is_available = $profile->is_available;
        }
    }

    protected function rules()
    {
        return [
            'chat_rate_15min' => 'nullable|numeric|min:0|max:10000',
            'chat_rate_30min' => 'nullable|numeric|min:0|max:10000',
            'chat_rate_60min' => 'nullable|numeric|min:0|max:10000',
            'video_rate_15min' => 'nullable|numeric|min:0|max:10000',
            'video_rate_30min' => 'nullable|numeric|min:0|max:10000',
            'video_rate_60min' => 'nullable|numeric|min:0|max:10000',
            'document_review_min_price' => 'nullable|numeric|min:0|max:50000',
        ];
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Get first validation error message
            $errors = $e->validator->errors()->all();
            session()->flash('error', $errors[0]);
            return;
        }

        try {
            $profile = Auth::user()->lawyerProfile;
            
            $profile->update([
                'chat_rate_15min' => $this->chat_rate_15min,
                'chat_rate_30min' => $this->chat_rate_30min,
                'chat_rate_60min' => $this->chat_rate_60min,
                'video_rate_15min' => $this->video_rate_15min,
                'video_rate_30min' => $this->video_rate_30min,
                'video_rate_60min' => $this->video_rate_60min,
                'document_review_min_price' => $this->document_review_min_price,
                'offers_chat_consultation' => $this->offers_chat_consultation,
                'offers_video_consultation' => $this->offers_video_consultation,
                'offers_document_review' => $this->offers_document_review,
                'auto_accept_bookings' => $this->auto_accept_bookings,
                'is_available' => $this->is_available,
            ]);

            session()->flash('success', 'Service settings updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Service settings update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.lawyer.profile-services')
            ->layout('layouts.dashboard', ['title' => 'Services & Pricing']);
    }
}
