<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LawyerProfile;
use App\Models\Consultation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PaymentTestSeeder extends Seeder
{
    /**
     * Seed test data for payment testing
     */
    public function run(): void
    {
        // Create test client
        $client = User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Test Client',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'client',
                'onboarding_completed_at' => now(),
                'phone' => '09171234567',
                'province' => 'Metro Manila',
                'city' => 'Quezon City',
            ]
        );

        // Create test lawyer with auto-accept enabled
        $lawyer = User::firstOrCreate(
            ['email' => 'lawyer@test.com'],
            [
                'name' => 'Test Lawyer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'lawyer',
                'onboarding_completed_at' => now(),
                'phone' => '09187654321',
                'province' => 'Metro Manila',
                'city' => 'Makati City',
            ]
        );

        // Create lawyer profile with auto-accept
        $lawyerProfile = LawyerProfile::firstOrCreate(
            ['user_id' => $lawyer->id],
            [
                'ibp_number' => 'IBP-TEST-12345',
                'years_experience' => 5,
                'bio' => 'Test lawyer for payment testing',
                'chat_rate_15min' => 300,
                'chat_rate_30min' => 500,
                'chat_rate_60min' => 900,
                'video_rate_15min' => 500,
                'video_rate_30min' => 900,
                'video_rate_60min' => 1600,
                'document_review_min_price' => 1000,
                'auto_accept_bookings' => true, // Enable auto-accept for easy testing
            ]
        );

        // Create a test consultation ready for payment
        $consultation = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Test Payment Consultation',
            'duration' => 30,
            'rate' => 900,
            'platform_fee' => 90,
            'total_amount' => 990,
            'status' => 'accepted',
            'scheduled_at' => now()->addDays(3)->setTime(14, 0),
            'accepted_at' => now(),
            'payment_deadline' => now()->addHour(),
            'client_notes' => 'This is a test consultation for payment testing.',
        ]);

        \App\Models\Transaction::create([
            'consultation_id' => $consultation->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 990,
            'lawyer_payout' => 900,
            'platform_fee' => 90,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        $this->command->info('✅ Payment test data created successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('Client: client@test.com / password');
        $this->command->info('Lawyer: lawyer@test.com / password');
        $this->command->info('');
        $this->command->info('Test Consultation ID: ' . $consultation->id);
        $this->command->info('Payment URL: /consultation/' . $consultation->id . '/payment');
    }
}
