<?php

namespace App\Livewire\Lawyer;

use Livewire\Component;

class ProfileSecurity extends Component
{
    public function render()
    {
        return view('livewire.lawyer.profile-security')
            ->layout('layouts.dashboard', ['title' => 'Security']);
    }
}
