<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ProfileSecurity extends Component
{
    public function render()
    {
        return view('livewire.admin.profile-security')
            ->layout('layouts.dashboard', ['title' => 'Security']);
    }
}
