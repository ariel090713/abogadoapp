<?php

namespace App\Livewire\Client;

use App\Helpers\PhilippineLocations;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $province;
    public $city;
    public $languages = [];
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
        $this->languages = $user->languages ?? [];
        $this->profile_photo = $user->profile_photo;
        $this->new_profile_photo = null;
    }

    protected function rules()
    {
        $userId = Auth::id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'required|regex:/^09[0-9]{9}$/',
            'province' => 'required|string',
            'city' => 'required|string',
            'languages' => 'required|array|min:1',
            'new_profile_photo' => 'nullable|image|max:5120', // 5MB
        ];
    }

    protected $messages = [
        'phone.regex' => 'Phone number must be in format: 09XXXXXXXXX',
        'new_profile_photo.max' => 'Profile photo must not exceed 5MB',
        'name.required' => 'Full name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'phone.required' => 'Phone number is required',
        'province.required' => 'Province is required',
        'city.required' => 'City is required',
        'languages.required' => 'Please select at least one language',
        'languages.min' => 'Please select at least one language',
    ];

    public function updatedProvince()
    {
        $this->city = '';
    }

    public function save(FileUploadService $fileService)
    {
        $this->validate();

        try {
            $user = Auth::user();
            
            \Log::info('Client profile save started', [
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
                'languages' => $this->languages,
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
            \Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to update profile. Please try again.');
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
        return view('livewire.client.profile')
            ->layout('layouts.dashboard', ['title' => 'Profile']);
    }
}
