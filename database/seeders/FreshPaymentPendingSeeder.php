<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LawyerProfile;
use App\Models\Consultation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FreshPaymentPendingSeeder extends Seeder
{
    /**
     * Seed fresh payment pending consultations for testing
     */
    public function run(): void
    {
        // Create or get test client
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

        // Create or get test lawyer
        $lawyer = User::firstOrCreate(
            ['email' => 'lawyer@test.com'],
            [
                'name' => 'Atty. Juan Dela Cruz',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'lawyer',
                'onboarding_completed_at' => now(),
                'phone' => '09187654321',
                'province' => 'Metro Manila',
                'city' => 'Makati City',
            ]
        );

        // Create or get lawyer profile
        $lawyerProfile = LawyerProfile::firstOrCreate(
            ['user_id' => $lawyer->id],
            [
                'username' => 'atty-juan-test',
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
                'auto_accept_bookings' => false,
            ]
        );

        // Delete existing payment_pending consultations for clean slate
        Consultation::where('client_id', $client->id)
            ->where('status', 'payment_pending')
            ->delete();

        $this->command->info('Creating fresh payment pending consultations...');

        // 1. Payment pending - 55 minutes remaining (urgent)
        $consultation1 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Urgent: Labor Law Consultation',
            'duration' => 30,
            'rate' => 900,
            'platform_fee' => 90,
            'total_amount' => 990,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addDays(2)->setTime(14, 0),
            'accepted_at' => now()->subMinutes(5),
            'payment_deadline' => now()->addMinutes(55), // 55 minutes left
            'client_notes' => 'Need urgent advice on wrongful termination case. My employer terminated me without proper notice.',
        ]);

        \App\Models\Transaction::create([
            'consultation_id' => $consultation1->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 990,
            'lawyer_payout' => 900,
            'platform_fee' => 90,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        // 2. Payment pending - 30 minutes remaining (moderate)
        $consultation2 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Contract Review Consultation',
            'duration' => 60,
            'rate' => 900,
            'platform_fee' => 90,
            'total_amount' => 990,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addDays(3)->setTime(10, 0),
            'accepted_at' => now()->subMinutes(30),
            'payment_deadline' => now()->addMinutes(30), // 30 minutes left
            'client_notes' => 'I need help reviewing an employment contract before signing. Want to make sure terms are fair.',
        ]);

        \App\Models\Transaction::create([
            'consultation_id' => $consultation2->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 990,
            'lawyer_payout' => 900,
            'platform_fee' => 90,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        // 3. Payment pending - 15 minutes remaining (very urgent)
        $consultation3 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Family Law - Child Custody',
            'duration' => 60,
            'rate' => 1600,
            'platform_fee' => 160,
            'total_amount' => 1760,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addDays(1)->setTime(15, 0),
            'accepted_at' => now()->subMinutes(45),
            'payment_deadline' => now()->addMinutes(15), // 15 minutes left!
            'client_notes' => 'Urgent consultation needed regarding child custody arrangement. Ex-spouse is threatening to take kids.',
        ]);

        \App\Models\Transaction::create([
            'consultation_id' => $consultation3->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 1760,
            'lawyer_payout' => 1600,
            'platform_fee' => 160,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        // 4. Payment pending - 45 minutes remaining
        $consultation4 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'document_review',
            'title' => 'Real Estate Document Review',
            'duration' => null,
            'rate' => 1000,
            'platform_fee' => 100,
            'total_amount' => 1100,
            'status' => 'payment_pending',
            'scheduled_at' => null,
            'accepted_at' => now()->subMinutes(15),
            'payment_deadline' => now()->addMinutes(45), // 45 minutes left
            'client_notes' => 'Need review of property sale documents. Want to ensure no hidden clauses or issues.',
        ]);

        \App\Models\Transaction::create([
            'consultation_id' => $consultation4->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 1100,
            'lawyer_payout' => 1000,
            'platform_fee' => 100,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        // 5. Payment pending - 5 minutes remaining (CRITICAL!)
        $consultation5 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Business Partnership Dispute',
            'duration' => 30,
            'rate' => 500,
            'platform_fee' => 50,
            'total_amount' => 550,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addDays(4)->setTime(16, 0),
            'accepted_at' => now()->subMinutes(55),
            'payment_deadline' => now()->addMinutes(5), // Only 5 minutes left!!!
            'client_notes' => 'Business partner wants to dissolve partnership. Need advice on protecting my interests.',
        ]);

        \App\Models\Transaction::create([
            'consultation_id' => $consultation5->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 550,
            'lawyer_payout' => 500,
            'platform_fee' => 50,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        $this->command->info('✅ Created 5 payment pending consultations:');
        $this->command->info('');
        $this->command->info('1. Labor Law - 55 minutes remaining (₱990)');
        $this->command->info('2. Contract Review - 30 minutes remaining (₱990)');
        $this->command->info('3. Child Custody - 15 minutes remaining (₱1,760) ⚠️');
        $this->command->info('4. Document Review - 45 minutes remaining (₱1,100)');
        $this->command->info('5. Business Dispute - 5 minutes remaining (₱550) 🚨 CRITICAL!');
        $this->command->info('');
        $this->command->info('Test Account:');
        $this->command->info('Email: client@test.com');
        $this->command->info('Password: password');
        $this->command->info('');
        $this->command->info('Go to: /client/consultations');
        $this->command->info('Filter: Payment Pending');
    }
}
