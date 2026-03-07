<?php

namespace App\Livewire\Components;

use App\Models\LawyerProfile;
use Livewire\Component;

class LawyerCard extends Component
{
    public LawyerProfile $lawyer;
    public string $viewMode = 'grid';

    public function render()
    {
        return view('livewire.components.lawyer-card');
    }
}
