<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ProfileNotifications extends Component
{
    public function render()
    {
        return view('livewire.admin.profile-notifications')
            ->layout('layouts.dashboard', ['title' => 'Notifications']);
    }
}
