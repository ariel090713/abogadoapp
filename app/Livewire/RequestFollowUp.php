<?php

namespace App\Livewire;

use App\Models\Consultation;
use Livewire\Component;

class RequestFollowUp extends Component
{
    public Consultation $consultation;

    public function mount(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    public function render()
    {
        return view('livewire.request-follow-up');
    }
}
