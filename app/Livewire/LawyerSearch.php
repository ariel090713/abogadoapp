<?php

namespace App\Livewire;

use App\Models\LawyerProfile;
use App\Models\Specialization;
use Livewire\Component;
use Livewire\WithPagination;

class LawyerSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $specializations = []; // Changed from single to array
    public $specializationSearch = ''; // New: for filtering specializations list
    public $location = '';
    public $languages = []; // Multi-select languages
    public $sortBy = 'rating';
    public $viewMode = 'grid';

    protected $queryString = [
        'search' => ['except' => ''],
        'specializations' => ['except' => []], // Changed
        'location' => ['except' => ''],
        'languages' => ['except' => []],
        'sortBy' => ['except' => 'rating'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSpecializations()
    {
        $this->resetPage();
    }

    public function updatingLocation()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'specializations', 'location', 'languages']);
        $this->resetPage();
    }

    public function toggleSpecialization($slug)
    {
        if (in_array($slug, $this->specializations)) {
            $this->specializations = array_values(array_diff($this->specializations, [$slug]));
        } else {
            $this->specializations[] = $slug;
        }
        $this->resetPage();
    }

    public function toggleLanguage($language)
    {
        if (in_array($language, $this->languages)) {
            $this->languages = array_values(array_diff($this->languages, [$language]));
        } else {
            $this->languages[] = $language;
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = LawyerProfile::with(['user', 'specializations', 'reviews'])
            ->where('is_verified', true)
            ->where('is_available', true)
            ->whereHas('user', function ($q) {
                $q->where('is_active', true); // Only active (not suspended) users
            });

        // Search by name
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by specializations (handles multiple selections)
        if (!empty($this->specializations)) {
            $query->whereHas('specializations', function ($q) {
                $selectedSpecs = \App\Models\Specialization::whereIn('slug', $this->specializations)->get();
                
                $allIds = [];
                foreach ($selectedSpecs as $spec) {
                    if ($spec->is_parent) {
                        // If parent selected, include parent and all children
                        $childIds = $spec->children->pluck('id')->toArray();
                        $allIds = array_merge($allIds, [$spec->id], $childIds);
                    } else {
                        // If child selected, include only that child
                        $allIds[] = $spec->id;
                    }
                }
                
                $q->whereIn('specializations.id', array_unique($allIds));
            });
        }

        // Filter by province
        if ($this->location) {
            $query->whereHas('user', function ($q) {
                $q->where('province', $this->location);
            });
        }

        // Filter by languages
        if (!empty($this->languages)) {
            $query->where(function ($q) {
                foreach ($this->languages as $language) {
                    $q->orWhereJsonContains('languages', $language);
                }
            });
        }

        // Sort
        switch ($this->sortBy) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('rate_per_15min', 'asc');
                break;
            case 'price_high':
                $query->orderBy('rate_per_15min', 'desc');
                break;
            case 'experience':
                $query->orderBy('years_experience', 'desc');
                break;
            case 'reviews':
                $query->orderBy('total_reviews', 'desc');
                break;
        }

        $lawyers = $query->paginate(30);
        
        // Get all specializations with children
        $allSpecializations = Specialization::with('children')
            ->where('is_parent', true)
            ->orderBy('name')
            ->get();
        
        // Filter specializations based on search
        if ($this->specializationSearch) {
            $searchTerm = strtolower($this->specializationSearch);
            $allSpecializations = $allSpecializations->filter(function ($parent) use ($searchTerm) {
                // Check if parent name matches
                if (str_contains(strtolower($parent->name), $searchTerm)) {
                    return true;
                }
                // Check if any child name matches
                return $parent->children->filter(function ($child) use ($searchTerm) {
                    return str_contains(strtolower($child->name), $searchTerm);
                })->isNotEmpty();
            })->map(function ($parent) use ($searchTerm) {
                // Filter children if parent doesn't match but children do
                if (!str_contains(strtolower($parent->name), $searchTerm)) {
                    $parent->setRelation('children', $parent->children->filter(function ($child) use ($searchTerm) {
                        return str_contains(strtolower($child->name), $searchTerm);
                    }));
                }
                return $parent;
            });
        }

        return view('livewire.lawyer-search', [
            'lawyers' => $lawyers,
            'allSpecializations' => $allSpecializations,
            'availableLanguages' => \App\Helpers\Languages::getLanguages(),
            'provinces' => \App\Helpers\PhilippineLocations::getProvincesList(),
        ])->layout('layouts.guest');
    }
}
