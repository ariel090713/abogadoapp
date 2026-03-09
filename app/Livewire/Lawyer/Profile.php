<?php

namespace App\Livewire\Lawyer;

use App\Helpers\Languages;
use App\Helpers\PhilippineLocations;
use App\Models\Specialization;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    // User fields
    public $name;
    public $email;
    public $phone;
    public $province;
    public $city;
    public $profile_photo;
    public $new_profile_photo;

    public function mount()
    {
        $user = Auth::user();
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->province = $user->province;
        $this->city = $user->city;
        $this->profile_photo = $user->profile_photo;
        $this->new_profile_photo = null;
    }

    protected function rules()
    {
        $userId = Auth::id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => ['required', 'regex:/^(\+639|09)[0-9]{9}$/'],
            'province' => 'required|string',
            'city' => 'required|string',
            'new_profile_photo' => 'nullable|image|max:5120',
        ];
    }

    protected $messages = [
        'phone.regex' => 'Phone number must be in format: 09XXXXXXXXX or +639XXXXXXXXX',
        'name.required' => 'Full name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'phone.required' => 'Phone number is required',
        'province.required' => 'Province is required',
        'city.required' => 'City is required',
    ];

    public function updatedProvince()
    {
        $this->city = '';
    }

    public function save(FileUploadService $fileService)
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
            $user = Auth::user();
            
            \Log::info('Profile save started', [
                'user_id' => $user->id,
                'has_new_photo' => !is_null($this->new_profile_photo),
            ]);
            
            // Handle profile photo upload
            if ($this->new_profile_photo) {
                \Log::info('Starting profile photo upload to S3', [
                    'user_id' => $user->id,
                    'file_size' => $this->new_profile_photo->getSize(),
                    'mime_type' => $this->new_profile_photo->getMimeType(),
                ]);
                
                $fileData = $fileService->uploadPublic(
                    $this->new_profile_photo,
                    'profile-photos'
                );
                
                \Log::info('Profile photo uploaded to S3 successfully', [
                    'user_id' => $user->id,
                    'path' => $fileData['path'],
                    'url' => $fileData['url'],
                ]);
                
                $photoUrl = $fileData['url'];
            } else {
                $photoUrl = $this->profile_photo;
                \Log::info('No new photo, keeping existing', [
                    'user_id' => $user->id,
                    'existing_url' => $photoUrl,
                ]);
            }

            // Update user data
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'province' => $this->province,
                'city' => $this->city,
                'profile_photo' => $photoUrl,
            ]);
            
            \Log::info('User profile updated in database', [
                'user_id' => $user->id,
                'profile_photo_url' => $photoUrl,
            ]);

            // Reset photo upload and update local property
            $this->profile_photo = $photoUrl;
            $this->reset('new_profile_photo');

            session()->flash('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Lawyer profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function getProvincesProperty()
    {
        return PhilippineLocations::getProvincesList();
    }

    public function getCitiesProperty()
    {
        if (!$this->province) {
            return [];
        }
        
        return PhilippineLocations::getCitiesByProvince($this->province);
    }

    public function render()
    {
        return view('livewire.lawyer.profile')
            ->layout('layouts.dashboard', ['title' => 'Profile']);
    }
}
