<?php

namespace App\Livewire\Admin;

use App\Models\AISetting;
use App\Models\AIKnowledgeBase;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AISettings extends Component
{
    use WithFileUploads, WithPagination;

    // AI Personality & Rules
    public $ai_name = '';
    public $ai_personality = '';
    public $ai_rules = '';
    public $ai_greeting = '';
    public $ai_enabled = true;

    // Knowledge Base
    public $kb_title = '';
    public $kb_content = '';
    public $kb_type = 'text'; // 'text' or 'file'
    public $kb_file;
    public $kb_priority = 5;
    public $editingKnowledgeId = null;

    // Active Tab
    public $activeTab = 'personality';

    protected $rules = [
        'ai_name' => 'required|string|max:255',
        'ai_personality' => 'required|string',
        'ai_rules' => 'required|string',
        'ai_greeting' => 'required|string',
        'kb_title' => 'required|string|max:255',
        'kb_content' => 'nullable|string|max:100000', // 100k characters max
        'kb_priority' => 'required|integer|min:0|max:10',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->ai_name = AISetting::get('ai_name', 'Legal Assistant');
        $this->ai_personality = AISetting::get('ai_personality', 'You are a helpful and professional legal assistant for AbogadoMo App, a platform connecting clients with verified Philippine lawyers.');
        $this->ai_rules = AISetting::get('ai_rules', "- Always be polite and professional\n- Only recommend lawyers based on specializations\n- Never provide legal advice\n- Ask clarifying questions when needed");
        $this->ai_greeting = AISetting::get('ai_greeting', 'Hello! I\'m here to help you find the right lawyer for your legal concern. Can you tell me about your situation?');
        $this->ai_enabled = (bool) AISetting::get('ai_enabled', true);
    }

    public function savePersonality()
    {
        $this->validate([
            'ai_name' => 'required|string|max:255',
            'ai_personality' => 'required|string',
            'ai_rules' => 'required|string',
            'ai_greeting' => 'required|string',
        ]);

        AISetting::set('ai_name', $this->ai_name, 'text', 'AI Assistant Name');
        AISetting::set('ai_personality', $this->ai_personality, 'textarea', 'AI Personality Description');
        AISetting::set('ai_rules', $this->ai_rules, 'textarea', 'AI Behavior Rules');
        AISetting::set('ai_greeting', $this->ai_greeting, 'textarea', 'AI Greeting Message');
        AISetting::set('ai_enabled', $this->ai_enabled ? '1' : '0', 'boolean', 'Enable AI Assistant');

        AISetting::clearCache();

        session()->flash('success', 'AI personality settings saved successfully!');
    }

    public function saveKnowledge()
    {
        // Validate based on type
        if ($this->kb_type === 'text') {
            $this->validate([
                'kb_title' => 'required|string|max:255',
                'kb_type' => 'required|in:text,file',
                'kb_content' => 'required|string|max:100000', // 100k characters
                'kb_priority' => 'required|integer|min:0|max:10',
            ]);
        } else {
            $this->validate([
                'kb_title' => 'required|string|max:255',
                'kb_type' => 'required|in:text,file',
                'kb_file' => 'required|file|max:10240|mimes:pdf,doc,docx,txt',
                'kb_priority' => 'required|integer|min:0|max:10',
            ]);
        }

        try {
            $data = [
                'title' => $this->kb_title,
                'priority' => $this->kb_priority,
                'type' => $this->kb_type,
            ];

            if ($this->kb_type === 'text') {
                $data['content'] = $this->kb_content;
            } else {
                // Handle file upload
                if ($this->kb_file) {
                    $fileService = app(FileUploadService::class);
                    $fileData = $fileService->uploadPrivate($this->kb_file, 'ai-knowledge-base');
                    
                    // Extract text content from file
                    $content = $this->extractTextFromFile($this->kb_file);
                    
                    $data['content'] = $content;
                    $data['file_path'] = $fileData['path'];
                    $data['file_name'] = $fileData['original_name'];
                    $data['file_size'] = $fileData['size'];
                }
            }

            if ($this->editingKnowledgeId) {
                $knowledge = AIKnowledgeBase::findOrFail($this->editingKnowledgeId);
                $knowledge->update($data);
                
                // Process chunks after update (2000 chars per chunk, 400 overlap)
                $knowledge->processAndStoreChunks(2000, 400);
                
                session()->flash('success', 'Knowledge base entry updated successfully!');
            } else {
                $knowledge = AIKnowledgeBase::create($data);
                
                // Process chunks after creation (2000 chars per chunk, 400 overlap)
                $knowledge->processAndStoreChunks(2000, 400);
                
                session()->flash('success', 'Knowledge base entry added successfully!');
            }

            $this->resetKnowledgeForm();
            
        } catch (\Exception $e) {
            \Log::error('AI Knowledge Base Save Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Failed to save knowledge base entry. Please try again.');
        }
    }

    private function extractTextFromFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        
        try {
            if ($extension === 'txt') {
                return file_get_contents($tempPath);
                
            } elseif ($extension === 'pdf') {
                // Use smalot/pdfparser to extract text from PDF
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($tempPath);
                $text = $pdf->getText();
                
                // Clean up the text
                $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces
                $text = trim($text);
                
                if (empty($text)) {
                    return "PDF file uploaded: " . $file->getClientOriginalName() . " (No extractable text found - may be image-based PDF)";
                }
                
                return $text;
                
            } elseif (in_array($extension, ['doc', 'docx'])) {
                // Use PhpWord to extract text from DOC/DOCX
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempPath);
                $text = '';
                
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . ' ';
                        } elseif (method_exists($element, 'getElements')) {
                            // Handle nested elements (like tables, lists)
                            foreach ($element->getElements() as $childElement) {
                                if (method_exists($childElement, 'getText')) {
                                    $text .= $childElement->getText() . ' ';
                                }
                            }
                        }
                    }
                }
                
                // Clean up the text
                $text = preg_replace('/\s+/', ' ', $text);
                $text = trim($text);
                
                if (empty($text)) {
                    return "Word document uploaded: " . $file->getClientOriginalName() . " (No extractable text found)";
                }
                
                return $text;
                
            } else {
                return "Document uploaded: " . $file->getClientOriginalName() . " (Unsupported format)";
            }
            
        } catch (\Exception $e) {
            \Log::error('File text extraction failed', [
                'file' => $file->getClientOriginalName(),
                'extension' => $extension,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return "File uploaded: " . $file->getClientOriginalName() . " (Text extraction failed: " . $e->getMessage() . ")";
        }
    }

    public function editKnowledge($id)
    {
        $knowledge = AIKnowledgeBase::findOrFail($id);
        
        $this->editingKnowledgeId = $knowledge->id;
        $this->kb_title = $knowledge->title;
        $this->kb_content = $knowledge->content ?? '';
        $this->kb_type = $knowledge->type ?? 'text';
        $this->kb_priority = $knowledge->priority;
        
        $this->activeTab = 'knowledge';
        
        // Scroll to form
        $this->dispatch('scroll-to-form');
    }

    public function deleteKnowledge($id)
    {
        try {
            $knowledge = AIKnowledgeBase::findOrFail($id);
            $knowledge->delete();
            
            session()->flash('success', 'Knowledge base entry deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete knowledge base entry.');
        }
    }

    public function toggleKnowledgeStatus($id)
    {
        $knowledge = AIKnowledgeBase::findOrFail($id);
        $knowledge->update(['is_active' => !$knowledge->is_active]);
        
        session()->flash('success', 'Knowledge base status updated!');
    }

    public function resetKnowledgeForm()
    {
        $this->reset(['kb_title', 'kb_content', 'kb_type', 'kb_file', 'kb_priority', 'editingKnowledgeId']);
        $this->kb_priority = 5;
        $this->kb_type = 'text';
    }

    public function render()
    {
        $knowledgeBase = AIKnowledgeBase::orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.ai-settings', [
            'knowledgeBase' => $knowledgeBase,
        ])->layout('layouts.dashboard', ['title' => 'AI Settings']);
    }
}
