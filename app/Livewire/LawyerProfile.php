<?php

namespace App\Livewire;

use App\Models\LawyerProfile as LawyerProfileModel;
use Livewire\Component;
use Livewire\WithPagination;

class LawyerProfile extends Component
{
    use WithPagination;

    public LawyerProfileModel $lawyer;
    public $reviewFilter = 'all'; // all, consultations, documents

    public function mount($username)
    {
        $this->lawyer = LawyerProfileModel::with([
            'user',
            'specializations',
            'availabilitySchedules'
        ])
        ->where('username', $username)
        ->where('is_verified', true)
        ->whereHas('user', function ($q) {
            $q->where('is_active', true); // Only show active (not suspended) lawyers
        })
        ->firstOrFail();
    }

    public function setReviewFilter($filter)
    {
        $this->reviewFilter = $filter;
        $this->resetPage();
    }

    public function getDocumentServicesProperty()
    {
        return \App\Models\LawyerDocumentService::with('template')
            ->where('lawyer_id', $this->lawyer->user_id)
            ->where('is_active', true)
            ->latest()
            ->get();
    }

    public function getReviewsProperty()
    {
        $query = \App\Models\Review::with(['client', 'consultation', 'documentRequest'])
            ->where('lawyer_profile_id', $this->lawyer->id);

        if ($this->reviewFilter === 'consultations') {
            $query->whereNotNull('consultation_id');
        } elseif ($this->reviewFilter === 'documents') {
            $query->whereNotNull('document_request_id');
        }

        return $query->latest()->paginate(10);
    }

    public function render()
    {
        return view('livewire.lawyer-profile')->layout('layouts.guest');
    }
}
