<?php

namespace App\Livewire\Admin;

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
    public $profile_photo;
    public $new_profile_photo;

    public function mount()
    {
        $user = Auth::user();
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->profile_photo = $user->profile_photo;
        $this->new_profile_photo = null;
    }

    protected function rules()
    {
        $userId = Auth::id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => ['nullable', 'regex:/^(\+639|09)[0-9]{9}$/'],
            'new_profile_photo' => 'nullable|image|max:5120', // 5MB
        ];
    }

    protected $messages = [
        'phone.regex' => 'Phone number must be in format: 09XXXXXXXXX or +639XXXXXXXXX',
        'new_profile_photo.max' => 'Profile photo must not exceed 5MB',
        'name.required' => 'Full name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
    ];

    public function save(FileUploadService $fileService)
    {
        $this->validate();

        try {
            $user = Auth::user();
            
            // Handle profile photo upload
            if ($this->new_profile_photo) {
                $fileData = $fileService->uploadPublic(
                    $this->new_profile_photo,
                    'profile-photos'
                );
                
                $photoUrl = $fileData['url'];
            } else {
                $photoUrl = $this->profile_photo;
            }

            // Update user data
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'profile_photo' => $photoUrl,
            ]);

            // Reset photo upload and update local property
            $this->profile_photo = $photoUrl;
            $this->reset('new_profile_photo');

            session()->flash('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Admin profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to update profile. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.profile')
            ->layout('layouts.dashboard', ['title' => 'Profile']);
    }
}
