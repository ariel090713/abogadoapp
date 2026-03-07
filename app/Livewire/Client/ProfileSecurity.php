<?php

namespace App\Livewire\Client;

use Livewire\Component;

class ProfileSecurity extends Component
{
    public function render()
    {
        return view('livewire.client.profile-security')
            ->layout('layouts.dashboard', ['title' => 'Security']);
    }
}
