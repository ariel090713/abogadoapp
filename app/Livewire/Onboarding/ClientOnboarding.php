<?php

namespace App\Livewire\Onboarding;

use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientOnboarding extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $phone = '';
    public $province = '';
    public $city = '';
    public $languages = [];
    public $interests = [];
    public $totalSteps = 3; // Removed profile photo step
    
    protected $rules = [
        'phone' => 'required|regex:/^(\+639|09|9)[0-9]{9}$/',
        'province' => 'required|string',
        'city' => 'required|string',
        'languages' => 'required|array|min:1',
    ];

    public function mount()
    {
        $user = auth()->user();
        // Keep the phone format as stored in database for display
        $this->phone = $user->phone ?? '';
        $this->province = $user->province ?? '';
        $this->city = $user->city ?? '';
        $this->languages = \App\Helpers\Languages::getDefault();
    }

    public function updatedProvince()
    {
        $this->city = '';
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'phone' => 'required|regex:/^(\+639|09|9)[0-9]{9}$/',
                'province' => 'required|string',
                'city' => 'required|string',
                'languages' => 'required|array|min:1',
            ]);
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
        $user = auth()->user();
        
        // Normalize phone number to +639 format
        $normalizedPhone = $this->phone;
        if (str_starts_with($normalizedPhone, '09')) {
            $normalizedPhone = '+63' . substr($normalizedPhone, 1);
        } elseif (str_starts_with($normalizedPhone, '9')) {
            $normalizedPhone = '+63' . $normalizedPhone;
        }
        // If already starts with +639, keep as is
        
        // Update basic info
        $user->update([
            'phone' => $normalizedPhone,
            'province' => $this->province,
            'city' => $this->city,
            'location' => $this->city . ', ' . $this->province,
            'onboarding_completed_at' => now(),
            'onboarding_data' => [
                'interests' => $this->interests,
                'languages' => $this->languages,
            ],
        ]);

        return redirect()->route('onboarding.success');
    }

    public function render()
    {
        $specializations = \App\Models\Specialization::where('is_parent', true)
            ->orderBy('name')
            ->get();

        $provinces = \App\Helpers\PhilippineLocations::getProvincesList();
        $cities = $this->province ? \App\Helpers\PhilippineLocations::getCitiesByProvince($this->province) : [];
        $availableLanguages = \App\Helpers\Languages::available();

        return view('livewire.onboarding.client-onboarding', [
            'specializations' => $specializations,
            'provinces' => $provinces,
            'cities' => $cities,
            'availableLanguages' => $availableLanguages,
        ])->layout('layouts.guest');
    }
}
