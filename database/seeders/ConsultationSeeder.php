<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get clients and lawyers
        $clients = User::where('role', 'client')->get();
        $lawyers = User::where('role', 'lawyer')->get();

        if ($clients->isEmpty() || $lawyers->isEmpty()) {
            $this->command->warn('No clients or lawyers found. Please run LawyerSeeder first.');
            return;
        }

        $client = $clients->first();
        $lawyer = $lawyers->first();

        // Create sample consultations with different statuses
        
        // Pending consultation
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'duration' => 30,
            'rate' => 1400.00,
            'platform_fee' => 140.00,
            'total_amount' => 1540.00,
            'status' => 'pending',
            'scheduled_at' => now()->addDays(3)->setTime(14, 0),
            'client_notes' => 'I need legal advice regarding a property dispute with my neighbor. The issue involves boundary lines and potential encroachment.',
        ]);

        // Accepted consultation (payment pending)
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'duration' => 15,
            'rate' => 500.00,
            'platform_fee' => 50.00,
            'total_amount' => 550.00,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addDays(2)->setTime(10, 0),
            'accepted_at' => now()->subHours(1),
            'payment_deadline' => now()->addMinutes(29),
            'client_notes' => 'Quick question about employment contract terms and conditions.',
        ]);

        // Scheduled consultation
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'duration' => 60,
            'rate' => 2500.00,
            'platform_fee' => 250.00,
            'total_amount' => 2750.00,
            'status' => 'scheduled',
            'scheduled_at' => now()->addDays(5)->setTime(15, 30),
            'accepted_at' => now()->subDays(1),
            'client_notes' => 'Comprehensive consultation about starting a business and legal requirements for incorporation.',
        ]);
        
        // Scheduled consultation (tomorrow - for testing reminders)
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'duration' => 30,
            'rate' => 1400.00,
            'platform_fee' => 140.00,
            'total_amount' => 1540.00,
            'status' => 'scheduled',
            'scheduled_at' => now()->addHours(23),
            'accepted_at' => now()->subDays(2),
            'client_notes' => 'Follow-up consultation about property dispute.',
        ]);
        
        // Scheduled consultation (in 1 hour - for testing reminders)
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'duration' => 15,
            'rate' => 500.00,
            'platform_fee' => 50.00,
            'total_amount' => 550.00,
            'status' => 'scheduled',
            'scheduled_at' => now()->addMinutes(55),
            'accepted_at' => now()->subDays(1),
            'client_notes' => 'Quick legal question about contract terms.',
        ]);

        // Completed consultation
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'duration' => 30,
            'rate' => 900.00,
            'platform_fee' => 90.00,
            'total_amount' => 990.00,
            'status' => 'completed',
            'scheduled_at' => now()->subDays(3)->setTime(11, 0),
            'accepted_at' => now()->subDays(4),
            'started_at' => now()->subDays(3)->setTime(11, 0),
            'ended_at' => now()->subDays(3)->setTime(11, 30),
            'client_notes' => 'Consultation about rental agreement review and tenant rights.',
            'lawyer_notes' => 'Provided advice on tenant rights and lease agreement clauses. Client satisfied with consultation.',
        ]);

        // Document review consultation
        Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'document_review',
            'duration' => null,
            'rate' => 1500.00,
            'platform_fee' => 150.00,
            'total_amount' => 1650.00,
            'quoted_price' => 1500.00,
            'status' => 'pending',
            'scheduled_at' => null,
            'client_notes' => 'Please review this employment contract and provide feedback on any concerning clauses.',
            'document_path' => 'consultation-documents/sample-contract.pdf',
        ]);

        $this->command->info('Sample consultations created successfully!');
        
        // Create more diverse consultations
        $consultationTypes = ['video', 'chat', 'document_review'];
        $statuses = ['pending', 'payment_pending', 'scheduled', 'completed', 'cancelled', 'declined'];
        
        $sampleConcerns = [
            'I need advice on labor law issues regarding termination without cause.',
            'Question about inheritance and estate planning for my family.',
            'Legal consultation needed for small business registration and permits.',
            'Dispute with contractor over unfinished home renovation project.',
            'Need help understanding my rights in a car accident case.',
            'Consultation about divorce proceedings and child custody.',
            'Review of partnership agreement for new business venture.',
            'Landlord-tenant dispute regarding security deposit return.',
            'Immigration law questions about visa application process.',
            'Intellectual property concerns about trademark registration.',
            'Consumer rights issue with defective product purchase.',
            'Employment contract review before signing new job offer.',
            'Real estate transaction advice for property purchase.',
            'Debt collection harassment and consumer protection.',
            'Criminal defense consultation for traffic violation case.',
            'Corporate law advice for company restructuring.',
            'Tax law consultation regarding business deductions.',
            'Medical malpractice case initial consultation.',
            'Personal injury claim from workplace accident.',
            'Contract dispute with service provider.',
        ];

        // Create 30 more consultations with varied data
        for ($i = 0; $i < 30; $i++) {
            $randomClient = $clients->random();
            $randomLawyer = $lawyers->random();
            $consultationType = $consultationTypes[array_rand($consultationTypes)];
            $status = $statuses[array_rand($statuses)];
            
            // Determine duration and rate based on type
            if ($consultationType === 'document_review') {
                $duration = null;
                $rate = rand(1000, 3000);
            } else {
                $durations = [15, 30, 45, 60];
                $duration = $durations[array_rand($durations)];
                $rate = $duration * (rand(25, 50)); // ₱25-50 per minute
            }
            
            $platformFee = $rate * 0.10;
            $totalAmount = $rate + $platformFee;
            
            // Base consultation data
            $consultationData = [
                'client_id' => $randomClient->id,
                'lawyer_id' => $randomLawyer->id,
                'consultation_type' => $consultationType,
                'duration' => $duration,
                'rate' => $rate,
                'platform_fee' => $platformFee,
                'total_amount' => $totalAmount,
                'status' => $status,
                'client_notes' => $sampleConcerns[array_rand($sampleConcerns)],
            ];
            
            // Add status-specific data
            switch ($status) {
                case 'pending':
                    $consultationData['scheduled_at'] = now()->addDays(rand(1, 10))->setTime(rand(9, 17), [0, 15, 30, 45][rand(0, 3)]);
                    break;
                    
                case 'payment_pending':
                    $consultationData['scheduled_at'] = now()->addDays(rand(1, 7))->setTime(rand(9, 17), [0, 15, 30, 45][rand(0, 3)]);
                    $consultationData['accepted_at'] = now()->subHours(rand(1, 5));
                    $consultationData['payment_deadline'] = now()->addMinutes(rand(5, 25));
                    break;
                    
                case 'scheduled':
                    $consultationData['scheduled_at'] = now()->addDays(rand(1, 14))->setTime(rand(9, 17), [0, 15, 30, 45][rand(0, 3)]);
                    $consultationData['accepted_at'] = now()->subDays(rand(1, 3));
                    break;
                    
                case 'completed':
                    $pastDate = now()->subDays(rand(1, 30));
                    $consultationData['scheduled_at'] = $pastDate->setTime(rand(9, 17), [0, 15, 30, 45][rand(0, 3)]);
                    $consultationData['accepted_at'] = $pastDate->copy()->subDays(rand(1, 3));
                    $consultationData['started_at'] = $consultationData['scheduled_at'];
                    $consultationData['ended_at'] = $consultationData['started_at']->copy()->addMinutes($duration ?? 30);
                    $consultationData['lawyer_notes'] = 'Consultation completed successfully. Client was satisfied with the advice provided.';
                    break;
                    
                case 'cancelled':
                    $consultationData['scheduled_at'] = now()->addDays(rand(1, 7))->setTime(rand(9, 17), [0, 15, 30, 45][rand(0, 3)]);
                    $consultationData['cancel_reason'] = ['Schedule conflict', 'Found another lawyer', 'Issue resolved', 'Personal reasons'][rand(0, 3)];
                    break;
                    
                case 'declined':
                    $consultationData['scheduled_at'] = now()->addDays(rand(1, 7))->setTime(rand(9, 17), [0, 15, 30, 45][rand(0, 3)]);
                    $consultationData['decline_reason'] = ['Schedule conflict', 'Outside my area of expertise', 'Too busy at the moment'][rand(0, 2)];
                    break;
            }
            
            // Add document path for document review
            if ($consultationType === 'document_review') {
                $consultationData['quoted_price'] = $rate;
                $consultationData['document_path'] = 'consultation-documents/sample-document-' . $i . '.pdf';
            }
            
            Consultation::create($consultationData);
        }
        
        $this->command->info('Created 37 sample consultations with various statuses!');
    }
}
