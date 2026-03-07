<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuotationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test accounts
        $client = User::where('email', 'client@test.com')->first();
        $lawyer = User::where('email', 'lawyer@test.com')->first();

        if (!$client || !$lawyer) {
            $this->command->error('Test accounts not found. Please run UserSeeder first.');
            return;
        }

        // Create consultations with different quotation statuses
        $consultations = [
            // 1. Pending - waiting for lawyer to provide quote or accept
            [
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'document_review',
                'title' => 'Contract Review - Pending Quote',
                'duration' => null,
                'rate' => 1000.00,
                'platform_fee' => 100.00,
                'total_amount' => 1100.00,
                'status' => 'pending',
                'client_notes' => 'I need a review of my employment contract. It has 10 pages with some complex clauses about non-compete and intellectual property.',
                'document_path' => 'consultation-documents/employment-contract.pdf',
                'created_at' => now()->subMinutes(10),
            ],

            // 2. Awaiting Quote Approval - lawyer provided quote, waiting for client
            [
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'document_review',
                'title' => 'Lease Agreement Review - Quote Provided',
                'duration' => null,
                'rate' => 1500.00,
                'platform_fee' => 150.00,
                'total_amount' => 1650.00,
                'quoted_price' => 1500.00,
                'quote_notes' => 'After reviewing your lease agreement, I found several clauses that need careful analysis. The document is 15 pages long with complex terms regarding property maintenance, rent escalation, and early termination penalties. This will require approximately 2-3 hours of detailed review and analysis. My quote includes a comprehensive written report with recommendations.',
                'quote_provided_at' => now()->subMinutes(30),
                'status' => 'awaiting_quote_approval',
                'client_notes' => 'I need help understanding a commercial lease agreement for my new business location.',
                'document_path' => 'consultation-documents/lease-agreement.pdf',
                'created_at' => now()->subHour(),
            ],

            // 3. Video consultation - pending quote
            [
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'video',
                'title' => 'Business Partnership Consultation',
                'duration' => 60,
                'rate' => 800.00,
                'platform_fee' => 80.00,
                'total_amount' => 880.00,
                'status' => 'pending',
                'client_notes' => 'I want to discuss forming a business partnership. I have several questions about liability, profit sharing, and exit strategies.',
                'created_at' => now()->subMinutes(20),
            ],

            // 4. Chat consultation - awaiting quote approval
            [
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'chat',
                'title' => 'Employment Rights Question - Quote Provided',
                'duration' => 30,
                'rate' => 600.00,
                'platform_fee' => 60.00,
                'total_amount' => 660.00,
                'quoted_price' => 600.00,
                'quote_notes' => 'Based on your inquiry about employment rights and potential wrongful termination, this consultation will require detailed discussion of labor laws, your employment contract, and company policies. I estimate we\'ll need about 45 minutes to cover all aspects thoroughly. My quote includes follow-up advice via email if needed within 24 hours.',
                'quote_provided_at' => now()->subMinutes(15),
                'status' => 'awaiting_quote_approval',
                'client_notes' => 'I was recently terminated from my job and I think it might be wrongful termination. I need advice on my rights and options.',
                'created_at' => now()->subMinutes(45),
            ],

            // 5. Document review - quote declined by client
            [
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'document_review',
                'title' => 'NDA Review - Quote Declined',
                'duration' => null,
                'rate' => 2000.00,
                'platform_fee' => 200.00,
                'total_amount' => 2200.00,
                'quoted_price' => 2000.00,
                'quote_notes' => 'This NDA contains highly technical provisions and international jurisdiction clauses that require specialized expertise. The review will take approximately 4 hours.',
                'quote_provided_at' => now()->subHours(2),
                'status' => 'declined',
                'decline_reason' => 'Client declined the quote',
                'client_notes' => 'I need a review of a non-disclosure agreement for a potential business deal.',
                'document_path' => 'consultation-documents/nda.pdf',
                'created_at' => now()->subHours(3),
            ],
        ];

        foreach ($consultations as $data) {
            Consultation::create($data);
        }

        $this->command->info('✅ Created 5 test consultations with various quotation statuses:');
        $this->command->info('   1. Document Review - Pending (waiting for quote)');
        $this->command->info('   2. Document Review - Awaiting Quote Approval (quote provided 30 min ago)');
        $this->command->info('   3. Video Call - Pending (waiting for quote)');
        $this->command->info('   4. Chat - Awaiting Quote Approval (quote provided 15 min ago)');
        $this->command->info('   5. Document Review - Quote Declined (client declined)');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('   Client: client@test.com / password');
        $this->command->info('   Lawyer: lawyer@test.com / password');
    }
}
