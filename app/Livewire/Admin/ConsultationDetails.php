<?php

namespace App\Livewire\Admin;

use App\Models\Consultation;
use Livewire\Component;

class ConsultationDetails extends Component
{
    public Consultation $consultation;
    
    public $showCancelModal = false;
    public $cancelReason = '';
    
    public $showNotifyModal = false;
    public $notifyType = ''; // 'client' or 'lawyer'
    public $notifyMessage = '';

    public function mount($id)
    {
        $this->consultation = Consultation::with([
            'client',
            'lawyer',
            'transaction',
            'messages',
            'documents'
        ])->findOrFail($id);
    }

    public function openCancelModal()
    {
        $this->cancelReason = '';
        $this->showCancelModal = true;
    }

    public function cancelConsultation()
    {
        $this->validate([
            'cancelReason' => 'required|string|min:10',
        ]);

        try {
            $this->consultation->update([
                'status' => 'cancelled',
                'cancellation_reason' => 'Admin: ' . $this->cancelReason,
                'cancelled_at' => now(),
                'cancelled_by' => 'admin',
            ]);

            // Notify both parties
            if ($this->consultation->client) {
                $this->consultation->client->notify(new \App\Notifications\ConsultationCancelled($this->consultation));
            }
            if ($this->consultation->lawyer) {
                $this->consultation->lawyer->notify(new \App\Notifications\ConsultationCancelled($this->consultation));
            }

            // Log admin action
            \App\Models\AdminAction::create([
                'admin_id' => auth()->id(),
                'action_type' => 'consultation_cancelled',
                'target_type' => 'consultation',
                'target_id' => $this->consultation->id,
                'metadata' => [
                    'reason' => $this->cancelReason,
                    'consultation_id' => $this->consultation->id,
                ],
            ]);

            session()->flash('success', 'Consultation cancelled successfully');
            $this->showCancelModal = false;
            $this->consultation->refresh();
            
        } catch (\Exception $e) {
            \Log::error('Admin consultation cancellation failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to cancel consultation');
        }
    }

    public function openNotifyModal($type)
    {
        $this->notifyType = $type;
        $this->notifyMessage = '';
        $this->showNotifyModal = true;
    }

    public function sendNotification()
    {
        $this->validate([
            'notifyMessage' => 'required|string|min:10',
        ]);

        try {
            if ($this->notifyType === 'client' && $this->consultation->client) {
                $this->consultation->client->notify(new \App\Notifications\AdminMessage(
                    'Consultation Update',
                    $this->notifyMessage,
                    route('client.consultation-thread.details', $this->consultation->id)
                ));
            } elseif ($this->notifyType === 'lawyer' && $this->consultation->lawyer) {
                $this->consultation->lawyer->notify(new \App\Notifications\AdminMessage(
                    'Consultation Update',
                    $this->notifyMessage,
                    route('lawyer.consultations', $this->consultation->id)
                ));
            }

            session()->flash('success', 'Notification sent successfully');
            $this->showNotifyModal = false;
            $this->notifyMessage = '';
            
        } catch (\Exception $e) {
            \Log::error('Admin notification failed', [
                'consultation_id' => $this->consultation->id,
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Failed to send notification');
        }
    }

    public function closeModal()
    {
        $this->showCancelModal = false;
        $this->showNotifyModal = false;
        $this->cancelReason = '';
        $this->notifyMessage = '';
    }

    public function render()
    {
        return view('livewire.admin.consultation-details')
            ->layout('layouts.dashboard', ['title' => 'Consultation Details']);
    }
}
