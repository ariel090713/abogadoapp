<?php

namespace App\Livewire\Lawyer;

use App\Models\AvailabilitySchedule;
use App\Models\BlockedDate;
use App\Models\Consultation;
use Carbon\Carbon;
use Livewire\Component;

class Schedule extends Component
{
    public $schedules = [];
    public $blockedDates = [];
    public $view = 'weekly'; // weekly or calendar
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];
    
    // Block date modal
    public $showBlockModal = false;
    public $blockDate = '';
    public $blockStartTime = '';
    public $blockEndTime = '';
    public $blockReason = '';
    public $isFullDay = true;
    
    public $days = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadSchedules();
        $this->loadBlockedDates();
        $this->generateCalendar();
    }

    public function loadSchedules()
    {
        $existingSchedules = auth()->user()->lawyerProfile->availabilitySchedules;
        
        foreach ($this->days as $dayNum => $dayName) {
            $schedule = $existingSchedules->firstWhere('day_of_week', $dayNum);
            
            $this->schedules[$dayNum] = [
                'id' => $schedule->id ?? null,
                'is_available' => $schedule ? $schedule->is_available : false,
                'start_time' => $schedule && $schedule->start_time ? substr($schedule->start_time, 0, 5) : '09:00',
                'end_time' => $schedule && $schedule->end_time ? substr($schedule->end_time, 0, 5) : '17:00',
            ];
        }
    }
    
    public function loadBlockedDates()
    {
        $this->blockedDates = auth()->user()->lawyerProfile->blockedDates()
            ->where('blocked_date', '>=', now()->format('Y-m-d'))
            ->orderBy('blocked_date')
            ->get()
            ->toArray();
    }
    
    public function generateCalendar()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeekIso; // 1 = Monday
        
        $this->calendarDays = [];
        
        // Add empty cells for days before month starts
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            $this->calendarDays[] = null;
        }
        
        // Get consultations for this month
        $consultations = Consultation::where('lawyer_id', auth()->id())
            ->whereYear('scheduled_at', $this->currentYear)
            ->whereMonth('scheduled_at', $this->currentMonth)
            ->whereIn('status', ['scheduled', 'pending', 'payment_pending'])
            ->get();
        
        // Get blocked dates for this month
        $blockedDates = auth()->user()->lawyerProfile->blockedDates()
            ->whereYear('blocked_date', $this->currentYear)
            ->whereMonth('blocked_date', $this->currentMonth)
            ->get();
        
        // Add days of month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($this->currentYear, $this->currentMonth, $day);
            $dayOfWeek = $currentDate->dayOfWeekIso;
            
            $dayConsultations = $consultations->filter(function($consultation) use ($day) {
                return $consultation->scheduled_at && $consultation->scheduled_at->day === $day;
            });
            
            $dayBlocked = $blockedDates->first(function($blocked) use ($day) {
                return $blocked->blocked_date->day === $day;
            });
            
            $this->calendarDays[] = [
                'day' => $day,
                'date' => $currentDate,
                'isToday' => $currentDate->isToday(),
                'isPast' => $currentDate->isPast() && !$currentDate->isToday(),
                'isAvailable' => $this->schedules[$dayOfWeek]['is_available'] ?? false,
                'consultations' => $dayConsultations->count(),
                'consultationDetails' => $dayConsultations->map(function($c) {
                    $endTime = $c->scheduled_at->copy()->addMinutes($c->duration);
                    return [
                        'id' => $c->id,
                        'title' => $c->title,
                        'client_name' => $c->client->name,
                        'start_time' => $c->scheduled_at->format('g:i A'),
                        'end_time' => $endTime->format('g:i A'),
                        'time_range' => $c->scheduled_at->format('g:i A') . ' - ' . $endTime->format('g:i A'),
                        'duration' => $c->duration,
                        'type' => $c->consultation_type,
                        'status' => $c->status,
                    ];
                })->values()->toArray(),
                'isBlocked' => $dayBlocked !== null,
                'isFullDayBlock' => $dayBlocked?->is_full_day ?? false,
                'blockedId' => $dayBlocked?->id,
                'blockReason' => $dayBlocked?->reason,
                'blockStartTime' => $dayBlocked && !$dayBlocked->is_full_day ? $dayBlocked->start_time : null,
                'blockEndTime' => $dayBlocked && !$dayBlocked->is_full_day ? $dayBlocked->end_time : null,
            ];
        }
    }
    
    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
    }
    
    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
    }
    
    public function setView($view)
    {
        $this->view = $view;
        if ($view === 'calendar') {
            $this->generateCalendar();
        }
    }
    
    public function openBlockModal($date = null)
    {
        \Log::info('openBlockModal called', ['date' => $date]);
        
        $this->showBlockModal = true;
        $this->blockDate = $date ?? now()->format('Y-m-d');
        $this->blockStartTime = '09:00';
        $this->blockEndTime = '17:00';
        $this->blockReason = '';
        $this->isFullDay = true;
        
        \Log::info('Modal opened', [
            'showBlockModal' => $this->showBlockModal,
            'blockDate' => $this->blockDate,
        ]);
    }
    
    public function closeBlockModal()
    {
        $this->showBlockModal = false;
        $this->reset(['blockDate', 'blockStartTime', 'blockEndTime', 'blockReason', 'isFullDay']);
    }
    
    public function saveBlockedDate()
    {
        \Log::info('=== BLOCK DATE METHOD CALLED ===', [
            'blockDate' => $this->blockDate,
            'isFullDay' => $this->isFullDay,
            'blockStartTime' => $this->blockStartTime,
            'blockEndTime' => $this->blockEndTime,
            'blockReason' => $this->blockReason,
            'user_id' => auth()->id(),
        ]);
        
        try {
            $this->validate([
                'blockDate' => 'required|date|after_or_equal:today',
                'blockStartTime' => 'required_if:isFullDay,false|nullable|date_format:H:i',
                'blockEndTime' => 'required_if:isFullDay,false|nullable|date_format:H:i|after:blockStartTime',
                'blockReason' => 'nullable|string|max:255',
            ]);
            
            \Log::info('=== VALIDATION PASSED ===');
            
            $blocked = auth()->user()->lawyerProfile->blockedDates()->create([
                'blocked_date' => $this->blockDate,
                'start_time' => $this->isFullDay ? null : $this->blockStartTime,
                'end_time' => $this->isFullDay ? null : $this->blockEndTime,
                'reason' => $this->blockReason,
                'is_full_day' => $this->isFullDay,
            ]);
            
            \Log::info('=== DATE BLOCKED SUCCESSFULLY ===', [
                'blocked_id' => $blocked->id,
                'date' => $this->blockDate,
                'is_full_day' => $this->isFullDay,
            ]);
            
            $this->closeBlockModal();
            $this->loadBlockedDates();
            $this->generateCalendar();
            
            session()->flash('success', 'Date blocked successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('=== VALIDATION FAILED ===', [
                'errors' => $e->errors(),
                'data' => [
                    'blockDate' => $this->blockDate,
                    'isFullDay' => $this->isFullDay,
                    'blockStartTime' => $this->blockStartTime,
                    'blockEndTime' => $this->blockEndTime,
                ],
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            \Log::error('=== BLOCK DATE FAILED ===', [
                'lawyer_id' => auth()->user()->lawyerProfile->id ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Failed to block date: ' . $e->getMessage());
        }
    }
    
    public function unblockDate($blockedDateId)
    {
        try {
            BlockedDate::where('id', $blockedDateId)
                ->where('lawyer_profile_id', auth()->user()->lawyerProfile->id)
                ->delete();
            
            session()->flash('success', 'Date unblocked successfully!');
            $this->loadBlockedDates();
            $this->generateCalendar();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unblock date. Please try again.');
        }
    }

    public function toggleDay($day)
    {
        $this->schedules[$day]['is_available'] = !$this->schedules[$day]['is_available'];
    }

    public function save()
    {
        // Validate only enabled schedules
        $rules = [];
        foreach ($this->schedules as $dayNum => $schedule) {
            if ($schedule['is_available']) {
                $rules["schedules.{$dayNum}.start_time"] = 'required|date_format:H:i';
                $rules["schedules.{$dayNum}.end_time"] = 'required|date_format:H:i|after:schedules.' . $dayNum . '.start_time';
            }
        }
        
        $this->validate($rules);

        try {
            foreach ($this->schedules as $dayNum => $schedule) {
                if ($schedule['id']) {
                    // Update existing
                    AvailabilitySchedule::where('id', $schedule['id'])->update([
                        'is_available' => $schedule['is_available'],
                        'start_time' => $schedule['is_available'] ? $schedule['start_time'] : null,
                        'end_time' => $schedule['is_available'] ? $schedule['end_time'] : null,
                    ]);
                } else {
                    // Create new
                    auth()->user()->lawyerProfile->availabilitySchedules()->create([
                        'day_of_week' => $dayNum,
                        'is_available' => $schedule['is_available'],
                        'start_time' => $schedule['is_available'] ? $schedule['start_time'] : null,
                        'end_time' => $schedule['is_available'] ? $schedule['end_time'] : null,
                    ]);
                }
            }

            session()->flash('success', 'Schedule updated successfully!');
            $this->loadSchedules();
            $this->generateCalendar();
            
        } catch (\Exception $e) {
            \Log::error('Schedule update failed', [
                'lawyer_id' => auth()->user()->lawyerProfile->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to update schedule. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.lawyer.schedule')
            ->layout('layouts.dashboard', ['title' => 'Schedule Management']);
    }
}
