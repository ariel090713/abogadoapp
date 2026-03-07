<?php

namespace App\Livewire\Lawyer;

use App\Models\DocumentTemplate;
use App\Models\LawyerDocumentService;
use Livewire\Component;

class CreateDocument extends Component
{
    public $step = 1; // 1: Choose template or create, 2: Configure form, 3: Set pricing

    // Step 1
    public $selectedTemplate = null;
    public $createFrom = 'template'; // template or scratch

    // Step 2 & 3
    public $name = '';
    public $description = '';
    public $category = '';
    public $formFields = [];
    public $price = '';
    public $estimatedClientTime = 15;
    public $estimatedCompletionDays = 3;
    public $revisionsAllowed = 1;

    // Form builder
    public $newFieldType = 'text';

    public function mount()
    {
        // Initialize with empty form fields
        $this->formFields = ['fields' => []];
    }

    public function selectTemplate($templateId)
    {
        $template = DocumentTemplate::findOrFail($templateId);
        
        $this->selectedTemplate = $template->id;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->category = $template->category;
        
        // Convert options array to string for select fields
        $formFields = $template->form_fields;
        foreach ($formFields['fields'] as &$field) {
            if ($field['type'] === 'select' && isset($field['options']) && is_array($field['options'])) {
                $field['options'] = implode("\n", $field['options']);
            }
        }
        $this->formFields = $formFields;
        
        // Increment usage count
        $template->incrementUsage();
        
        $this->step = 2;
    }

    public function createFromScratch()
    {
        $this->createFrom = 'scratch';
        $this->step = 2;
    }

    public function addField()
    {
        $fieldId = 'field_' . uniqid();
        
        $newField = [
            'id' => $fieldId,
            'type' => $this->newFieldType,
            'label' => '',
            'placeholder' => '',
            'required' => true,
            'help_text' => '',
        ];

        // Add type-specific properties
        if ($this->newFieldType === 'textarea') {
            $newField['rows'] = 3;
        } elseif ($this->newFieldType === 'select') {
            $newField['options'] = ''; // Store as string, will convert to array on save
        } elseif ($this->newFieldType === 'number') {
            $newField['min'] = null;
            $newField['max'] = null;
        }

        $fields = $this->formFields['fields'] ?? [];
        $fields[] = $newField;
        $this->formFields = ['fields' => $fields];
    }

    public function removeField($index)
    {
        $fields = $this->formFields['fields'];
        unset($fields[$index]);
        $this->formFields = ['fields' => array_values($fields)];
    }

    public function moveFieldUp($index)
    {
        if ($index > 0) {
            $fields = $this->formFields['fields'];
            $temp = $fields[$index];
            $fields[$index] = $fields[$index - 1];
            $fields[$index - 1] = $temp;
            $this->formFields = ['fields' => $fields];
        }
    }

    public function moveFieldDown($index)
    {
        $fields = $this->formFields['fields'];
        if ($index < count($fields) - 1) {
            $temp = $fields[$index];
            $fields[$index] = $fields[$index + 1];
            $fields[$index + 1] = $temp;
            $this->formFields = ['fields' => $fields];
        }
    }

    public function nextStep()
    {
        if ($this->step === 2) {
            $this->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'category' => 'required|string',
                'formFields.fields' => 'required|array|min:1',
            ]);
        }

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string',
            'formFields.fields' => 'required|array|min:1',
            'price' => 'required|numeric|min:100|max:100000',
            'estimatedClientTime' => 'required|integer|min:5|max:120',
            'estimatedCompletionDays' => 'required|integer|min:1|max:30',
            'revisionsAllowed' => 'required|integer|min:0|max:5',
        ]);

        // Process form fields - convert options string to array for select fields
        $processedFields = $this->formFields;
        foreach ($processedFields['fields'] as &$field) {
            if ($field['type'] === 'select' && isset($field['options'])) {
                // Convert string (one per line) to array
                $optionsString = $field['options'];
                $field['options'] = array_filter(
                    array_map('trim', explode("\n", $optionsString)),
                    fn($option) => !empty($option)
                );
            }
        }

        LawyerDocumentService::create([
            'lawyer_id' => auth()->id(),
            'template_id' => $this->selectedTemplate,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'form_fields' => $processedFields,
            'price' => $this->price,
            'estimated_client_time' => $this->estimatedClientTime,
            'estimated_completion_days' => $this->estimatedCompletionDays,
            'revisions_allowed' => $this->revisionsAllowed,
            'is_active' => true,
        ]);

        session()->flash('success', 'Document service created successfully!');
        return redirect()->route('lawyer.documents');
    }

    public function render()
    {
        $templates = DocumentTemplate::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $categories = \App\Models\DocumentCategory::active()->ordered()->get();

        return view('livewire.lawyer.create-document', [
            'templates' => $templates,
            'categories' => $categories,
        ])->layout('layouts.dashboard', ['title' => 'Create Document Service']);
    }
}
