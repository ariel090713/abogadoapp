<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create test users
        $client = User::where('email', 'client@test.com')->first();
        if (!$client) {
            $client = User::factory()->create([
                'name' => 'Test Client',
                'email' => 'client@test.com',
                'password' => bcrypt('password'),
                'role' => 'client',
                'email_verified_at' => now(),
                'onboarding_completed' => true,
                'onboarding_completed_at' => now(),
            ]);
        }

        $lawyer = User::where('email', 'lawyer@test.com')->first();
        if (!$lawyer) {
            $lawyer = User::factory()->create([
                'name' => 'Atty. Test Lawyer',
                'email' => 'lawyer@test.com',
                'password' => bcrypt('password'),
                'role' => 'lawyer',
                'email_verified_at' => now(),
                'onboarding_completed' => true,
                'onboarding_completed_at' => now(),
            ]);

            // Create lawyer profile if doesn't exist
            if (!$lawyer->lawyerProfile) {
                $lawyer->lawyerProfile()->create([
                    'ibp_number' => 'IBP-' . rand(100000, 999999),
                    'years_of_experience' => 5,
                    'bio' => 'Experienced lawyer specializing in various legal matters.',
                    'verification_status' => 'verified',
                    'video_rate' => 1500.00,
                    'chat_rate' => 1000.00,
                    'document_review_rate' => 2000.00,
                ]);
            }
        }

        $this->command->info('Creating video consultation test scenarios...');

        // Scenario 1: Video consultation scheduled for 5 minutes from now (ACTIVE SOON)
        $scheduledSoon = now()->addMinutes(5);
        $consultation1 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Video Consultation - Starting Soon',
            'client_notes' => 'Need urgent legal advice about a contract dispute. This consultation will start in 5 minutes.',
            'duration' => 30,
            'rate' => 1500.00,
            'total_amount' => 1500.00,
            'status' => 'scheduled',
            'payment_status' => 'paid',
            'payment_intent_id' => 'pi_test_' . Str::random(20),
            'scheduled_at' => $scheduledSoon,
            'accepted_at' => now()->subHours(2),
            'payment_deadline' => now()->addDays(1),
            'video_room_sid' => 'RM' . Str::random(32), // Simulated Twilio Room SID
        ]);

        Transaction::create([
            'consultation_id' => $consultation1->id,
            'user_id' => $client->id,
            'type' => 'consultation_payment',
            'amount' => 1500.00,
            'lawyer_payout' => 1500.00,
            'platform_fee' => 0.00,
            'status' => 'captured',
            'payment_method' => 'gcash',
            'paymongo_payment_intent_id' => $consultation1->payment_intent_id,
            'processed_at' => now()->subHours(1),
        ]);

        $this->command->info("✓ Created video consultation starting in 5 minutes (ID: {$consultation1->id})");

        // Scenario 2: Video consultation happening NOW (ACTIVE)
        $scheduledNow = now()->subMinutes(5);
        $consultation2 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Video Consultation - Active Now',
            'client_notes' => 'Legal consultation about employment law. This consultation is currently active.',
            'duration' => 60,
            'rate' => 1500.00,
            'total_amount' => 1500.00,
            'status' => 'scheduled',
            'payment_status' => 'paid',
            'payment_intent_id' => 'pi_test_' . Str::random(20),
            'scheduled_at' => $scheduledNow,
            'accepted_at' => now()->subHours(3),
            'payment_deadline' => now()->addDays(1),
            'video_room_sid' => 'RM' . Str::random(32),
        ]);

        Transaction::create([
            'consultation_id' => $consultation2->id,
            'user_id' => $client->id,
            'type' => 'consultation_payment',
            'amount' => 1500.00,
            'lawyer_payout' => 1500.00,
            'platform_fee' => 0.00,
            'status' => 'captured',
            'payment_method' => 'card',
            'paymongo_payment_intent_id' => $consultation2->payment_intent_id,
            'processed_at' => now()->subHours(2),
        ]);

        $this->command->info("✓ Created active video consultation (ID: {$consultation2->id})");

        // Scenario 3: Video consultation scheduled for tomorrow
        $scheduledTomorrow = now()->addDay()->setTime(14, 0);
        $consultation3 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Video Consultation - Tomorrow',
            'client_notes' => 'Need advice on property law matters. Scheduled for tomorrow afternoon.',
            'duration' => 45,
            'rate' => 1500.00,
            'total_amount' => 1500.00,
            'status' => 'scheduled',
            'payment_status' => 'paid',
            'payment_intent_id' => 'pi_test_' . Str::random(20),
            'scheduled_at' => $scheduledTomorrow,
            'accepted_at' => now()->subHours(5),
            'payment_deadline' => now()->addDays(2),
            'video_room_sid' => 'RM' . Str::random(32),
        ]);

        Transaction::create([
            'consultation_id' => $consultation3->id,
            'user_id' => $client->id,
            'type' => 'consultation_payment',
            'amount' => 1500.00,
            'lawyer_payout' => 1500.00,
            'platform_fee' => 0.00,
            'status' => 'captured',
            'payment_method' => 'gcash',
            'paymongo_payment_intent_id' => $consultation3->payment_intent_id,
            'processed_at' => now()->subHours(4),
        ]);

        $this->command->info("✓ Created video consultation for tomorrow (ID: {$consultation3->id})");

        // Scenario 4: Video consultation that just ended (ENDED)
        $scheduledEnded = now()->subMinutes(35);
        $consultation4 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Video Consultation - Just Ended',
            'client_notes' => 'Consultation about family law. This consultation just ended.',
            'duration' => 30,
            'rate' => 1500.00,
            'total_amount' => 1500.00,
            'status' => 'scheduled',
            'payment_status' => 'paid',
            'payment_intent_id' => 'pi_test_' . Str::random(20),
            'scheduled_at' => $scheduledEnded,
            'accepted_at' => now()->subHours(4),
            'payment_deadline' => now()->addDays(1),
            'video_room_sid' => 'RM' . Str::random(32),
        ]);

        Transaction::create([
            'consultation_id' => $consultation4->id,
            'user_id' => $client->id,
            'type' => 'consultation_payment',
            'amount' => 1500.00,
            'lawyer_payout' => 1500.00,
            'platform_fee' => 0.00,
            'status' => 'captured',
            'payment_method' => 'card',
            'paymongo_payment_intent_id' => $consultation4->payment_intent_id,
            'processed_at' => now()->subHours(3),
        ]);

        $this->command->info("✓ Created ended video consultation (ID: {$consultation4->id})");

        // Scenario 5: Video consultation pending payment
        $consultation5 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Video Consultation - Payment Pending',
            'client_notes' => 'Need legal advice about business contracts. Waiting for payment.',
            'duration' => 30,
            'rate' => 1500.00,
            'total_amount' => 1500.00,
            'status' => 'payment_pending',
            'payment_status' => 'unpaid',
            'payment_intent_id' => null,
            'scheduled_at' => now()->addHours(24),
            'accepted_at' => now()->subMinutes(30),
            'payment_deadline' => now()->addHours(23),
            'video_room_sid' => null, // No room yet - will be created after payment
        ]);

        $this->command->info("✓ Created video consultation pending payment (ID: {$consultation5->id})");

        // Scenario 6: Chat consultation for comparison (ACTIVE NOW)
        $chatScheduledNow = now()->subMinutes(3);
        $consultation6 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Chat Consultation - Active Now',
            'client_notes' => 'Quick legal questions via chat. Currently active.',
            'duration' => 30,
            'rate' => 1000.00,
            'total_amount' => 1000.00,
            'status' => 'scheduled',
            'payment_status' => 'paid',
            'payment_intent_id' => 'pi_test_' . Str::random(20),
            'scheduled_at' => $chatScheduledNow,
            'accepted_at' => now()->subHours(2),
            'payment_deadline' => now()->addDays(1),
        ]);

        Transaction::create([
            'consultation_id' => $consultation6->id,
            'user_id' => $client->id,
            'type' => 'consultation_payment',
            'amount' => 1000.00,
            'lawyer_payout' => 1000.00,
            'platform_fee' => 0.00,
            'status' => 'captured',
            'payment_method' => 'gcash',
            'paymongo_payment_intent_id' => $consultation6->payment_intent_id,
            'processed_at' => now()->subHours(1),
        ]);

        $this->command->info("✓ Created active chat consultation for comparison (ID: {$consultation6->id})");

        $this->command->info('');
        $this->command->info('=== VIDEO CONSULTATION TEST DATA CREATED ===');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info("  Client: client@test.com / password");
        $this->command->info("  Lawyer: lawyer@test.com / password");
        $this->command->info('');
        $this->command->info('Test Scenarios:');
        $this->command->info("  1. Video starting in 5 min (ID: {$consultation1->id}) - Test waiting state");
        $this->command->info("  2. Video active NOW (ID: {$consultation2->id}) - Test video call");
        $this->command->info("  3. Video tomorrow (ID: {$consultation3->id}) - Test future booking");
        $this->command->info("  4. Video just ended (ID: {$consultation4->id}) - Test ended state");
        $this->command->info("  5. Video payment pending (ID: {$consultation5->id}) - Test payment flow");
        $this->command->info("  6. Chat active NOW (ID: {$consultation6->id}) - Compare with video");
        $this->command->info('');
        $this->command->info('URLs to test:');
        $this->command->info("  Client Dashboard: /client/dashboard");
        $this->command->info("  Lawyer Dashboard: /lawyer/dashboard");
        $this->command->info("  Video (Active): /client/consultations/{$consultation2->id}/video");
        $this->command->info("  Chat (Active): /client/consultations/{$consultation6->id}/chat");
        $this->command->info('');
        $this->command->info('NOTE: Twilio video rooms are simulated. Real Twilio integration');
        $this->command->info('      will create actual rooms when payment is completed.');
    }
}
