<?php

namespace App\Livewire\Lawyer;

use App\Helpers\Languages;
use App\Models\Specialization;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileProfessional extends Component
{
    public $usernameAvailable = null;
    public $usernameInvalid = false;

    // Lawyer profile fields
    public $ibp_number;
    public $law_school;
    public $law_firm;
    public $graduation_year;
    public $years_experience;
    public $username;
    public $bio;
    public $languages = [];
    public $selectedSpecializations = [];

    public function mount()
    {
        $profile = Auth::user()->lawyerProfile;
        
        if ($profile) {
            $this->ibp_number = $profile->ibp_number;
            $this->law_school = $profile->law_school;
            $this->law_firm = $profile->law_firm;
            $this->graduation_year = $profile->graduation_year;
            $this->years_experience = $profile->years_experience;
            $this->username = $profile->username;
            $this->bio = $profile->bio;
            $this->languages = $profile->languages ?? [];
            $this->selectedSpecializations = $profile->specializations->pluck('id')->toArray();
        }
    }

    protected function rules()
    {
        $profileId = Auth::user()->lawyerProfile->id ?? null;
        
        return [
            'ibp_number' => 'required|string|max:50',
            'law_school' => 'required|string|max:255',
            'law_firm' => 'nullable|string|max:255',
            'graduation_year' => 'required|integer|min:1950|max:' . date('Y'),
            'years_experience' => 'required|integer|min:1|max:50',
            'username' => 'required|string|alpha_dash|unique:lawyer_profiles,username,' . $profileId,
            'bio' => 'nullable|string|max:1000',
            'languages' => 'required|array|min:1',
            'selectedSpecializations' => 'required|array|min:1',
        ];
    }

    protected $messages = [
        'ibp_number.required' => 'IBP number is required',
        'username.alpha_dash' => 'Username can only contain letters, numbers, dashes and underscores',
        'languages.required' => 'Please select at least one language',
        'selectedSpecializations.required' => 'Please select at least one specialization',
        'law_school.required' => 'Law school is required',
        'graduation_year.required' => 'Graduation year is required',
        'graduation_year.min' => 'Graduation year must be 1950 or later',
        'graduation_year.max' => 'Graduation year cannot be in the future',
        'years_experience.required' => 'Years of experience is required',
        'years_experience.min' => 'Years of experience must be at least 1',
        'years_experience.max' => 'Years of experience cannot exceed 50',
        'username.required' => 'Username is required',
    ];

    public function updatedUsername()
    {
        $this->usernameAvailable = null;
        $this->usernameInvalid = false;
        
        if (empty($this->username)) {
            return;
        }
        
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->username)) {
            $this->usernameInvalid = true;
            $this->usernameAvailable = false;
            return;
        }
        
        $profileId = Auth::user()->lawyerProfile->id ?? null;
        $exists = \App\Models\LawyerProfile::where('username', $this->username)
            ->when($profileId, function($query) use ($profileId) {
                $query->where('id', '!=', $profileId);
            })
            ->exists();
        
        $this->usernameAvailable = !$exists;
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
                'ibp_number' => $this->ibp_number,
                'law_school' => $this->law_school,
                'law_firm' => $this->law_firm,
                'graduation_year' => $this->graduation_year,
                'years_experience' => $this->years_experience,
                'username' => $this->username,
                'bio' => $this->bio,
                'languages' => $this->languages,
            ]);

            $profile->specializations()->sync($this->selectedSpecializations);

            session()->flash('success', 'Professional information updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Professional info update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function getAvailableLanguagesProperty()
    {
        return Languages::getLanguages();
    }

    public function getAllSpecializationsProperty()
    {
        return Specialization::where('is_parent', true)
            ->with('children')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.lawyer.profile-professional')
            ->layout('layouts.dashboard', ['title' => 'Professional Info']);
    }
}
