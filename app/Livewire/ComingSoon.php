<?php

namespace App\Livewire;

use Livewire\Component;

class ComingSoon extends Component
{
    public function render()
    {
        return view('livewire.coming-soon')->layout('layouts.guest');
    }
}
