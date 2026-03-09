<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActiveVideoConsultationSeeder extends Seeder
{
    /**
     * Create an active video consultation for testing Twilio integration
     */
    public function run(): void
    {
        // Get or create test users
        $client = User::where('email', 'client@test.com')->first();
        $lawyer = User::where('email', 'lawyer@test.com')->first();

        if (!$client) {
            $client = User::factory()->create([
                'name' => 'Test Client',
                'email' => 'client@test.com',
                'role' => 'client',
                'email_verified_at' => now(),
                'onboarding_completed_at' => now(),
            ]);
        }

        if (!$lawyer) {
            $lawyer = User::factory()->create([
                'name' => 'Test Lawyer',
                'email' => 'lawyer@test.com',
                'role' => 'lawyer',
                'email_verified_at' => now(),
                'onboarding_completed_at' => now(),
            ]);

            // Create lawyer profile
            $lawyer->lawyerProfile()->create([
                'ibp_number' => 'TEST-123456',
                'years_of_experience' => 5,
                'practice_areas' => ['Corporate Law', 'Civil Law'],
                'bio' => 'Test lawyer for video consultation',
                'consultation_fee' => 1000,
                'is_verified' => true,
                'is_available' => true,
            ]);
        }

        // Create active video consultation (started 5 minutes ago, 60 minutes duration)
        $paymentIntentId = 'test_payment_intent_' . uniqid();
        $consultation = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'status' => 'in_progress',
            'title' => 'Active Video Consultation Test',
            'description' => 'This is a test video consultation that is currently active.',
            'scheduled_at' => now()->subMinutes(5), // Started 5 minutes ago
            'duration' => 60, // 60 minutes
            'started_at' => now()->subMinutes(5),
            'quoted_fee' => 1000,
            'final_fee' => 1000,
        ]);

        // Create completed transaction
        \App\Models\Transaction::create([
            'consultation_id' => $consultation->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 1000,
            'lawyer_payout' => 1000,
            'platform_fee' => 0,
            'status' => 'completed',
            'payment_method' => 'paymongo',
            'paymongo_payment_intent_id' => $paymentIntentId,
            'processed_at' => now()->subHours(1),
        ]);

        $this->command->info('✅ Active video consultation created!');
        $this->command->info('');
        $this->command->info('Test Users:');
        $this->command->info("Client: {$client->email} / password");
        $this->command->info("Lawyer: {$lawyer->email} / password");
        $this->command->info('');
        $this->command->info('Consultation Details:');
        $this->command->info("ID: {$consultation->id}");
        $this->command->info("Status: {$consultation->status}");
        $this->command->info("Started: {$consultation->started_at->diffForHumans()}");
        $this->command->info("Time Remaining: " . (55) . " minutes");
        $this->command->info('');
        $this->command->info('Access URLs:');
        $this->command->info("Client: " . route('client.consultation.video', $consultation->id));
        $this->command->info("Lawyer: " . route('lawyer.consultation.video', $consultation->id));
    }
}
