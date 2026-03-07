<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Consultation;
use App\Models\ServiceRequest;
use App\Models\LawyerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CaseManagementTestSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users
        $client = User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Test Client',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
                'onboarding_completed' => true,
            ]
        );

        $lawyer = User::firstOrCreate(
            ['email' => 'lawyer@test.com'],
            [
                'name' => 'Atty. Test Lawyer',
                'password' => Hash::make('password'),
                'role' => 'lawyer',
                'email_verified_at' => now(),
                'onboarding_completed' => true,
            ]
        );

        // Create lawyer profile if doesn't exist
        if (!$lawyer->lawyerProfile) {
            LawyerProfile::create([
                'user_id' => $lawyer->id,
                'ibp_number' => 'TEST-12345',
                'years_of_experience' => 5,
                'about' => 'Test lawyer for case management',
                'chat_rate' => 800,
                'video_rate' => 1500,
                'document_review_rate' => 1200,
            ]);
        }

        // Scenario 1: Completed consultation with pending follow-up request from client
        $consultation1 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Employment Contract Review',
            'status' => 'completed',
            'rate' => 1500,
            'platform_fee' => 150,
            'total_amount' => 1650,
            'scheduled_at' => now()->subDays(3),
            'started_at' => now()->subDays(3),
            'ended_at' => now()->subDays(3)->addMinutes(30),
            'completed_at' => now()->subDays(3)->addMinutes(35),
            'duration' => 30,
            'client_notes' => 'Need help reviewing my employment contract',
            'completion_notes' => 'Reviewed contract. Found several issues that need clarification.',
            'case_number' => 'CASE-2026-000001',
            'session_number' => 1,
            'is_follow_up' => false,
        ]);

        // Client requests follow-up chat (free)
        ServiceRequest::create([
            'consultation_id' => $consultation1->id,
            'requested_by' => $client->id,
            'request_type' => 'follow_up',
            'service_type' => 'chat',
            'description' => 'I have some quick questions about the issues you found in my contract. Can we have a quick chat?',
            'proposed_price' => null, // Free
            'proposed_date' => null,
            'status' => 'pending',
            'created_at' => now()->subHours(2),
        ]);

        // Scenario 2: Completed consultation with pending follow-up request from lawyer
        $consultation2 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'document_review',
            'title' => 'Lease Agreement Review',
            'status' => 'completed',
            'rate' => 1200,
            'platform_fee' => 120,
            'total_amount' => 1320,
            'scheduled_at' => now()->subDays(5),
            'completed_at' => now()->subDays(5)->addHours(2),
            'duration' => 0,
            'client_notes' => 'Please review my lease agreement',
            'completion_notes' => 'Reviewed lease agreement. Need to discuss findings via video call.',
            'case_number' => 'CASE-2026-000002',
            'session_number' => 1,
            'is_follow_up' => false,
        ]);

        // Lawyer requests follow-up video (paid)
        ServiceRequest::create([
            'consultation_id' => $consultation2->id,
            'requested_by' => $lawyer->id,
            'request_type' => 'follow_up',
            'service_type' => 'video',
            'description' => 'I found several critical issues in your lease agreement that we need to discuss in detail. I recommend a video consultation to go through each point.',
            'proposed_price' => 1500,
            'proposed_date' => now()->addDays(2)->setTime(14, 0),
            'status' => 'pending',
            'created_at' => now()->subHours(5),
        ]);

        // Scenario 3: Case with multiple sessions (main + follow-up)
        $consultation3 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Business Partnership Dispute',
            'status' => 'completed',
            'rate' => 800,
            'platform_fee' => 80,
            'total_amount' => 880,
            'scheduled_at' => now()->subDays(10),
            'completed_at' => now()->subDays(10)->addMinutes(45),
            'duration' => 45,
            'client_notes' => 'Having issues with my business partner',
            'completion_notes' => 'Initial consultation completed. Advised on next steps.',
            'case_number' => 'CASE-2026-000003',
            'session_number' => 1,
            'is_follow_up' => false,
        ]);

        // Follow-up session 2 (completed)
        $consultation3_followup1 = Consultation::create([
            'parent_consultation_id' => $consultation3->id,
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'document_review',
            'title' => 'Business Partnership Dispute',
            'status' => 'completed',
            'rate' => 1200,
            'platform_fee' => 120,
            'total_amount' => 1320,
            'scheduled_at' => now()->subDays(7),
            'completed_at' => now()->subDays(7)->addHours(1),
            'duration' => 0,
            'completion_notes' => 'Reviewed partnership agreement. Found discrepancies.',
            'session_number' => 2,
            'is_follow_up' => true,
            'follow_up_type' => 'document_review',
        ]);

        // Follow-up session 3 (completed)
        $consultation3_followup2 = Consultation::create([
            'parent_consultation_id' => $consultation3->id,
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Business Partnership Dispute',
            'status' => 'completed',
            'rate' => 1500,
            'platform_fee' => 150,
            'total_amount' => 1650,
            'scheduled_at' => now()->subDays(4),
            'completed_at' => now()->subDays(4)->addMinutes(40),
            'duration' => 30,
            'completion_notes' => 'Discussed legal options. Client will decide on next steps.',
            'session_number' => 3,
            'is_follow_up' => true,
            'follow_up_type' => 'clarification',
        ]);

        // Pending follow-up request for session 4
        ServiceRequest::create([
            'consultation_id' => $consultation3->id,
            'requested_by' => $client->id,
            'request_type' => 'follow_up',
            'service_type' => 'chat',
            'description' => 'I\'ve decided to proceed with mediation. Can we discuss the next steps?',
            'proposed_price' => null,
            'proposed_date' => null,
            'status' => 'pending',
            'created_at' => now()->subHours(1),
        ]);

        // Scenario 4: Multiple pending requests
        $consultation4 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Property Dispute Consultation',
            'status' => 'completed',
            'rate' => 1500,
            'platform_fee' => 150,
            'total_amount' => 1650,
            'scheduled_at' => now()->subDays(2),
            'completed_at' => now()->subDays(2)->addMinutes(35),
            'duration' => 30,
            'completion_notes' => 'Discussed property boundary issues.',
            'case_number' => 'CASE-2026-000004',
            'session_number' => 1,
            'is_follow_up' => false,
        ]);

        // Client requests document review
        ServiceRequest::create([
            'consultation_id' => $consultation4->id,
            'requested_by' => $client->id,
            'request_type' => 'follow_up',
            'service_type' => 'document_review',
            'description' => 'I have the property title and survey documents ready for your review.',
            'proposed_price' => null,
            'proposed_date' => null,
            'status' => 'pending',
            'created_at' => now()->subMinutes(30),
        ]);

        $this->command->info('✅ Case Management test data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('Client: client@test.com / password');
        $this->command->info('Lawyer: lawyer@test.com / password');
        $this->command->info('');
        $this->command->info('Scenarios Created:');
        $this->command->info('1. Completed consultation with pending client follow-up request (free chat)');
        $this->command->info('2. Completed consultation with pending lawyer follow-up request (paid video)');
        $this->command->info('3. Case with 3 completed sessions + pending follow-up request');
        $this->command->info('4. Completed consultation with pending document review request');
    }
}
