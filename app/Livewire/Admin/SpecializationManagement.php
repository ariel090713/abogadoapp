<?php

namespace App\Livewire\Admin;

use App\Models\Specialization;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SpecializationManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterType = 'all'; // all, parent, child
    
    // Form fields
    public $showModal = false;
    public $editMode = false;
    public $selectedId = null;
    
    public $name = '';
    public $description = '';
    public $image = null;
    public $image_url = '';
    public $parent_id = null;
    public $is_parent = false;
    
    // Delete confirmation
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $queryString = ['search', 'filterType'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->selectedId = $id;
        
        $specialization = Specialization::findOrFail($id);
        
        $this->name = $specialization->name;
        $this->description = $specialization->description;
        $this->image_url = $specialization->image_url;
        $this->parent_id = $specialization->parent_id;
        $this->is_parent = $specialization->is_parent;
        
        $this->showModal = true;
    }

    public function save(FileUploadService $fileService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'parent_id' => 'nullable|exists:specializations,id',
            'is_parent' => 'boolean',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'parent_id' => $this->parent_id,
                'is_parent' => $this->is_parent || !$this->parent_id, // Auto set is_parent if no parent
            ];

            // Handle image upload
            if ($this->image) {
                $fileData = $fileService->uploadPublic($this->image, 'specializations');
                $data['image_url'] = $fileData['url'];
            }

            if ($this->editMode) {
                $specialization = Specialization::findOrFail($this->selectedId);
                $specialization->update($data);
                session()->flash('success', 'Specialization updated successfully.');
            } else {
                Specialization::create($data);
                session()->flash('success', 'Specialization created successfully.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $specialization = Specialization::findOrFail($this->deleteId);
            
            // Check if it has children
            if ($specialization->children()->count() > 0) {
                session()->flash('error', 'Cannot delete specialization with sub-specializations. Delete children first.');
                $this->showDeleteModal = false;
                return;
            }
            
            // Check if it's used by lawyers
            if ($specialization->lawyerProfiles()->count() > 0) {
                session()->flash('error', 'Cannot delete specialization that is assigned to lawyers.');
                $this->showDeleteModal = false;
                return;
            }
            
            $specialization->delete();
            session()->flash('success', 'Specialization deleted successfully.');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'image', 'image_url', 'parent_id', 'is_parent', 'selectedId']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        // Get parent specializations with their children
        $parentSpecializations = Specialization::with(['children' => function($query) {
            $query->orderBy('name');
        }])
        ->where(function($query) {
            $query->where('is_parent', true)->orWhereNull('parent_id');
        })
        ->orderBy('name')
        ->get();

        // If searching, get all matching specializations
        $searchResults = null;
        if ($this->search) {
            $searchResults = Specialization::with(['parent', 'children'])
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orderBy('is_parent', 'desc')
                ->orderBy('name')
                ->get();
        }

        $allParentSpecializations = Specialization::where('is_parent', true)
                                              ->orWhereNull('parent_id')
                                              ->orderBy('name')
                                              ->get();

        return view('livewire.admin.specialization-management', [
            'parentSpecializations' => $parentSpecializations,
            'searchResults' => $searchResults,
            'allParentSpecializations' => $allParentSpecializations,
        ]);
    }
}
