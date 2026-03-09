<?php

namespace Database\Seeders;

use App\Models\AISetting;
use App\Models\AIKnowledgeBase;
use Illuminate\Database\Seeder;

class AISettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Default AI Settings
        AISetting::create([
            'key' => 'ai_name',
            'value' => 'Legal Assistant',
            'type' => 'text',
            'description' => 'AI Assistant Name',
        ]);

        AISetting::create([
            'key' => 'ai_personality',
            'value' => 'You are a helpful and professional legal assistant for AbogadoMo App, a platform connecting clients with verified Philippine lawyers. You are friendly, empathetic, and knowledgeable about Philippine legal system. You speak in a warm, conversational tone while maintaining professionalism.',
            'type' => 'textarea',
            'description' => 'AI Personality Description',
        ]);

        AISetting::create([
            'key' => 'ai_rules',
            'value' => "- Always be polite, professional, and empathetic\n- NEVER provide legal advice - only help users find the right lawyer\n- Ask clarifying questions to understand the user's legal concern\n- Recommend lawyers based on specializations that match the user's needs\n- If unsure, ask for more details rather than making assumptions\n- Keep responses concise and easy to understand\n- Use Filipino context and examples when relevant\n- Always end by offering to help find a lawyer",
            'type' => 'textarea',
            'description' => 'AI Behavior Rules',
        ]);

        AISetting::create([
            'key' => 'ai_greeting',
            'value' => 'Hello! I\'m here to help you find the right lawyer for your legal concern. Can you tell me about your situation? What kind of legal help do you need?',
            'type' => 'textarea',
            'description' => 'AI Greeting Message',
        ]);

        AISetting::create([
            'key' => 'ai_enabled',
            'value' => '1',
            'type' => 'boolean',
            'description' => 'Enable AI Assistant',
        ]);

        // Sample Knowledge Base Entries with RAG processing
        $this->command->info('Creating knowledge base entries with chunking...');
        
        $knowledgeEntries = [
            [
                'title' => 'About AbogadoMo App',
                'content' => 'AbogadoMo App is a Philippine-based online legal consultation platform that connects clients with verified lawyers. We offer video consultations, chat consultations, and document review services. All our lawyers are verified members of the Integrated Bar of the Philippines (IBP).',
                'type' => 'text',
                'priority' => 10,
                'metadata' => [
                    'category' => 'Platform Information',
                    'tags' => ['about', 'platform', 'services'],
                ],
            ],
            [
                'title' => 'Available Consultation Types',
                'content' => "We offer three types of consultations:\n\n1. Video Consultation - Face-to-face consultation via video call\n2. Chat Consultation - Text-based consultation for quick questions\n3. Document Review - Lawyer reviews your documents and provides feedback\n\nPrices vary by lawyer and consultation type. Clients can see rates before booking.",
                'type' => 'text',
                'priority' => 9,
                'metadata' => [
                    'category' => 'Services',
                    'tags' => ['consultation', 'services', 'pricing'],
                ],
            ],
            [
                'title' => 'How to Choose a Lawyer',
                'content' => "When recommending lawyers, consider:\n\n1. Specialization - Match the lawyer's practice area to the client's legal concern\n2. Experience - Years of practice and number of cases handled\n3. Rating - Client reviews and ratings\n4. Availability - Check if the lawyer has available time slots\n5. Price - Consider the client's budget\n6. Language - Ensure the lawyer speaks the client's preferred language",
                'type' => 'text',
                'priority' => 8,
                'metadata' => [
                    'category' => 'Guidance',
                    'tags' => ['choosing lawyer', 'recommendations', 'criteria'],
                ],
            ],
            [
                'title' => 'Common Legal Concerns in the Philippines',
                'content' => "Common legal issues Filipino clients face:\n\n- Labor and Employment (illegal dismissal, unpaid wages, benefits)\n- Family Law (annulment, child custody, support)\n- Criminal Law (theft, physical injuries, cyber libel)\n- Civil Law (contracts, property disputes, debt collection)\n- Business Law (business registration, contracts, compliance)\n- Immigration (visa issues, deportation)\n- Real Estate (land titles, property disputes)\n- Consumer Protection (defective products, fraud)",
                'type' => 'text',
                'priority' => 7,
                'metadata' => [
                    'category' => 'Legal Topics',
                    'tags' => ['legal concerns', 'practice areas', 'philippines'],
                ],
            ],
        ];

        foreach ($knowledgeEntries as $entry) {
            $kb = AIKnowledgeBase::create([
                'title' => $entry['title'],
                'content' => $entry['content'],
                'type' => $entry['type'],
                'priority' => $entry['priority'],
                'is_active' => true,
                'metadata' => $entry['metadata'],
                'mime_type' => 'text/plain',
            ]);
            
            // Process and store chunks (500 chars per chunk, 100 chars overlap)
            $kb->processAndStoreChunks(500, 100);
            
            $chunkCount = is_array($kb->chunks) ? count($kb->chunks) : 0;
            $this->command->info("✓ {$entry['title']} ({$chunkCount} chunks)");
        }
        
        $this->command->info('AI settings and knowledge base seeded successfully!');
    }
}
