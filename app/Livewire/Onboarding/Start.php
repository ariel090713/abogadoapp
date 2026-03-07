<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;

class Start extends Component
{
    public function mount()
    {
        // Don't auto-redirect - always show role selection first
        // This ensures users see the role selection page after email verification
    }
    
    public function selectRole(string $role)
    {
        auth()->user()->update(['role' => $role]);
        
        if ($role === 'lawyer') {
            return $this->redirect(route('onboarding.lawyer'));
        }
        
        return $this->redirect(route('onboarding.client'));
    }
    
    public function render()
    {
        return view('livewire.onboarding.start')
            ->layout('layouts.guest');
    }
}
