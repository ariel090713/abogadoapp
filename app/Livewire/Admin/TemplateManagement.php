<?php

namespace App\Livewire\Admin;

use App\Models\DocumentTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class TemplateManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    
    // Form fields
    public $showModal = false;
    public $editMode = false;
    public $templateId = null;
    public $name = '';
    public $description = '';
    public $category = '';
    public $sampleOutput = '';
    public $formFields = [];
    public $isActive = true;
    
    // Delete confirmation
    public $showDeleteModal = false;
    public $templateToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'sampleOutput' => 'nullable|string',
            'formFields' => 'required|array|min:1',
            'formFields.*.name' => 'required|string',
            'formFields.*.label' => 'required|string',
            'formFields.*.type' => 'required|string',
            'formFields.*.required' => 'boolean',
            'isActive' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'description', 'category', 'sampleOutput', 'formFields', 'isActive', 'editMode', 'templateId']);
        $this->isActive = true;
        $this->formFields = [
            ['name' => '', 'label' => '', 'type' => 'text', 'required' => true]
        ];
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        
        $this->templateId = $template->id;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->category = $template->category;
        $this->sampleOutput = $template->sample_output;
        $this->formFields = $template->form_fields ?? [];
        $this->isActive = $template->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'description', 'category', 'sampleOutput', 'formFields', 'isActive', 'editMode', 'templateId']);
    }

    public function addField()
    {
        $this->formFields[] = ['name' => '', 'label' => '', 'type' => 'text', 'required' => true];
    }

    public function removeField($index)
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields);
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->category,
                'sample_output' => $this->sampleOutput,
                'form_fields' => $this->formFields,
                'is_active' => $this->isActive,
            ];

            if ($this->editMode) {
                $template = DocumentTemplate::findOrFail($this->templateId);
                $template->update($data);
                session()->flash('success', 'Template updated successfully');
            } else {
                $data['created_by'] = auth()->id();
                DocumentTemplate::create($data);
                session()->flash('success', 'Template created successfully');
            }

            $this->closeModal();
            $this->resetPage();

        } catch (\Exception $e) {
            \Log::error('Template save failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to save template. Please try again.');
        }
    }

    public function confirmDelete($id)
    {
        $this->templateToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->templateToDelete = null;
        $this->showDeleteModal = false;
    }

    public function executeDelete()
    {
        try {
            $template = DocumentTemplate::findOrFail($this->templateToDelete);
            
            // Check if template is being used
            if ($template->lawyerServices()->count() > 0) {
                session()->flash('error', 'Cannot delete template that is being used by lawyers.');
                $this->cancelDelete();
                return;
            }

            $template->delete();
            session()->flash('success', 'Template deleted successfully');
            $this->cancelDelete();
            $this->resetPage();

        } catch (\Exception $e) {
            \Log::error('Template delete failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to delete template. Please try again.');
            $this->cancelDelete();
        }
    }

    public function toggleStatus($id)
    {
        try {
            $template = DocumentTemplate::findOrFail($id);
            $template->update(['is_active' => !$template->is_active]);
            
            $status = $template->is_active ? 'activated' : 'deactivated';
            session()->flash('success', "Template {$status} successfully");

        } catch (\Exception $e) {
            \Log::error('Template status toggle failed', [
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to update template status.');
        }
    }

    public function render()
    {
        $query = DocumentTemplate::with('creator');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by category
        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        // Filter by status
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $templates = $query->orderBy('category')
            ->orderBy('name')
            ->paginate(20);

        // Get unique categories for filter - use predefined list
        $categories = [
            'Contracts',
            'Affidavits',
            'Agreements',
            'Legal Letters',
            'Deeds',
            'Petitions',
            'Complaints',
            'Motions',
            'Memoranda',
            'Waivers & Releases',
            'Power of Attorney',
            'Notarial Documents',
            'Corporate Documents',
            'Employment Documents',
            'Real Estate Documents',
            'Other Documents',
        ];

        return view('livewire.admin.template-management', [
            'templates' => $templates,
            'categories' => $categories,
        ])->layout('layouts.dashboard');
    }
}
