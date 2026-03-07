<?php

namespace App\Livewire;

use Livewire\Component;

class LegalGuides extends Component
{
    public function render()
    {
        return view('livewire.legal-guides')->layout('layouts.guest');
    }
}
