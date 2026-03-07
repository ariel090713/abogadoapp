<?php

namespace App\Livewire\Components;

use Carbon\Carbon;
use Livewire\Component;

class CountdownTimer extends Component
{
    public $deadline;
    public $label;
    public $type; // 'lawyer_response', 'quote_response', 'payment'
    public $consultationId;
    
    // Computed properties
    public $expired = false;
    public $timeRemaining = [];
    public $urgency = 'safe'; // safe, warning, urgent, expired

    public function mount($deadline, $label = 'Deadline', $type = null, $consultationId = null)
    {
        $this->deadline = $deadline instanceof Carbon ? $deadline : Carbon::parse($deadline);
        $this->label = $label;
        $this->type = $type;
        $this->consultationId = $consultationId;
        
        $this->calculateTimeRemaining();
    }

    public function calculateTimeRemaining()
    {
        $now = now();
        
        if ($this->deadline->isPast()) {
            $this->expired = true;
            $this->urgency = 'expired';
            $this->timeRemaining = [
                'formatted' => 'Expired',
                'total_seconds' => 0,
            ];
            return;
        }

        $diff = $now->diff($this->deadline);
        $totalSeconds = $now->diffInSeconds($this->deadline);
        $totalHours = $now->diffInHours($this->deadline);
        
        // Format time remaining
        if ($totalHours >= 24) {
            $formatted = sprintf('%d days %d hours', $diff->d, $diff->h);
        } elseif ($totalHours >= 1) {
            $formatted = sprintf('%d hours %d minutes', $diff->h, $diff->i);
        } elseif ($diff->i >= 1) {
            $formatted = sprintf('%d minutes', $diff->i);
        } else {
            $formatted = sprintf('%d seconds', $diff->s);
        }

        // Determine urgency level
        if ($totalHours > 12) {
            $this->urgency = 'safe';
        } elseif ($totalHours > 3) {
            $this->urgency = 'warning';
        } else {
            $this->urgency = 'urgent';
        }

        $this->timeRemaining = [
            'formatted' => $formatted,
            'total_seconds' => $totalSeconds,
            'total_hours' => $totalHours,
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
        ];
    }

    public function render()
    {
        return view('livewire.components.countdown-timer');
    }
}
