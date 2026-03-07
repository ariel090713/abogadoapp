<?php

namespace App\Livewire;

use App\Models\LawyerDocumentService;
use App\Models\DocumentDraftingRequest;
use App\Services\DeadlineCalculationService;
use Livewire\Component;

class RequestDocument extends Component
{
    public LawyerDocumentService $document;
    public $formData = [];
    public $clientNotes = '';
    public $agreedToTerms = false;

    public function mount($id)
    {
        $this->document = LawyerDocumentService::with(['lawyer.lawyerProfile', 'template'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Initialize form data
        foreach ($this->document->form_fields['fields'] as $field) {
            $this->formData[$field['id']] = '';
        }
    }

    public function submit()
    {
        // Build validation rules dynamically
        $rules = [
            'clientNotes' => 'nullable|string|max:1000',
            'agreedToTerms' => 'accepted',
        ];

        foreach ($this->document->form_fields['fields'] as $field) {
            $fieldRules = [];
            
            if ($field['required']) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Add type-specific validation
            switch ($field['type']) {
                case 'number':
                    $fieldRules[] = 'numeric';
                    if (isset($field['min'])) $fieldRules[] = 'min:' . $field['min'];
                    if (isset($field['max'])) $fieldRules[] = 'max:' . $field['max'];
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
                case 'textarea':
                case 'text':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:5000';
                    break;
            }

            $rules['formData.' . $field['id']] = implode('|', $fieldRules);
        }

        $this->validate($rules);

        try {
            // Calculate payment deadline (24 hours from now)
            $paymentDeadline = now()->addHours(24);

            // Create document request
            $request = DocumentDraftingRequest::create([
                'client_id' => auth()->id(),
                'lawyer_id' => $this->document->lawyer_id,
                'lawyer_document_service_id' => $this->document->id,
                'document_name' => $this->document->name,
                'form_data' => $this->formData,
                'price' => $this->document->price,
                'status' => 'pending_payment',
                'revisions_used' => 0,
                'revisions_allowed' => $this->document->revisions_allowed,
                'payment_status' => 'pending',
                'payment_deadline' => $paymentDeadline,
                'client_notes' => $this->clientNotes,
            ]);

            // Increment order count
            $this->document->incrementOrders();

            // Send notification to lawyer
            $this->document->lawyer->notify(new \App\Notifications\DocumentRequestReceived($request));

            session()->flash('success', 'Document request submitted! Please proceed to payment.');
            
            // Redirect to payment
            return redirect()->route('document.payment', $request->id);

        } catch (\Exception $e) {
            \Log::error('Document request failed', [
                'document_id' => $this->document->id,
                'client_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to submit request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.request-document')
            ->layout('layouts.guest', ['title' => 'Request Document - ' . $this->document->name]);
    }
}
