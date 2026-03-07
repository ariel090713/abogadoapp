<?php

namespace App\Livewire\Lawyer;

use App\Models\LawyerDocumentService;
use Livewire\Component;

class EditDocument extends Component
{
    public LawyerDocumentService $document;
    
    public $name = '';
    public $description = '';
    public $category = '';
    public $formFields = [];
    public $price = '';
    public $estimatedClientTime = 15;
    public $estimatedCompletionDays = 3;
    public $revisionsAllowed = 1;
    public $isActive = true;

    // Form builder
    public $newFieldType = 'text';

    public function mount($id)
    {
        $this->document = LawyerDocumentService::where('lawyer_id', auth()->id())
            ->findOrFail($id);
        
        $this->name = $this->document->name;
        $this->description = $this->document->description;
        $this->category = $this->document->category;
        
        // Convert options array to string for select fields
        $formFields = $this->document->form_fields;
        foreach ($formFields['fields'] as &$field) {
            if ($field['type'] === 'select' && isset($field['options']) && is_array($field['options'])) {
                $field['options'] = implode("\n", $field['options']);
            }
        }
        $this->formFields = $formFields;
        
        $this->price = $this->document->price;
        $this->estimatedClientTime = $this->document->estimated_client_time;
        $this->estimatedCompletionDays = $this->document->estimated_completion_days;
        $this->revisionsAllowed = $this->document->revisions_allowed;
        $this->isActive = $this->document->is_active;
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

        $this->document->update([
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'form_fields' => $processedFields,
            'price' => $this->price,
            'estimated_client_time' => $this->estimatedClientTime,
            'estimated_completion_days' => $this->estimatedCompletionDays,
            'revisions_allowed' => $this->revisionsAllowed,
            'is_active' => $this->isActive,
        ]);

        session()->flash('success', 'Document service updated successfully!');
        return redirect()->route('lawyer.documents');
    }

    public function render()
    {
        $categories = \App\Models\DocumentCategory::active()->ordered()->get();
        
        return view('livewire.lawyer.edit-document', [
            'categories' => $categories,
        ])->layout('layouts.dashboard', ['title' => 'Edit Document Service']);
    }
}
