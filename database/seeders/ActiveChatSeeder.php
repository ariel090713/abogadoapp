<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActiveChatSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create test users
        $client = User::where('email', 'client@test.com')->first();
        $lawyer = User::where('email', 'lawyer@test.com')->first();

        if (!$client || !$lawyer) {
            $this->command->error('Test users not found. Please run the main seeder first.');
            return;
        }

        // Create an ACTIVE consultation (started 10 minutes ago, lasts 60 minutes)
        $consultation = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Active Chat Consultation - Testing',
            'duration' => 60,
            'rate' => 1500.00,
            'total_amount' => 1500.00,
            'status' => 'in_progress',
            'scheduled_at' => now()->subMinutes(10), // Started 10 minutes ago
            'started_at' => now()->subMinutes(10),
            'client_notes' => 'Active consultation for real-time chat testing',
        ]);

        // Create completed transaction
        \App\Models\Transaction::create([
            'consultation_id' => $consultation->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 1500.00,
            'lawyer_payout' => 1500.00,
            'platform_fee' => 0.00,
            'status' => 'completed',
            'payment_method' => 'gcash',
            'paymongo_payment_intent_id' => 'pi_test_' . uniqid(),
            'processed_at' => now()->subMinutes(15),
        ]);

        // Create sample messages
        $messages = [
            [
                'sender_id' => $client->id,
                'message' => 'Hello Attorney! Thank you for starting the consultation.',
                'created_at' => now()->subMinutes(9),
            ],
            [
                'sender_id' => $lawyer->id,
                'message' => 'Hello! You\'re welcome. How can I help you today?',
                'created_at' => now()->subMinutes(8),
            ],
            [
                'sender_id' => $client->id,
                'message' => 'I need advice about a contract issue.',
                'created_at' => now()->subMinutes(7),
            ],
        ];

        foreach ($messages as $messageData) {
            ConsultationMessage::create([
                'consultation_id' => $consultation->id,
                'sender_id' => $messageData['sender_id'],
                'message' => $messageData['message'],
                'attachments' => null,
                'read_at' => $messageData['created_at']->addSeconds(30),
                'created_at' => $messageData['created_at'],
                'updated_at' => $messageData['created_at'],
            ]);
        }

        $this->command->info('✅ Created active consultation with ' . count($messages) . ' messages');
        $this->command->info('');
        $this->command->info('📱 Test the ACTIVE chat at:');
        $this->command->info("   Client: /client/consultations/{$consultation->id}/chat");
        $this->command->info("   Lawyer: /lawyer/consultations/{$consultation->id}/chat");
        $this->command->info('');
        $this->command->info('⏰ Consultation Details:');
        $this->command->info("   Started: " . $consultation->scheduled_at->format('Y-m-d H:i:s'));
        $this->command->info("   Duration: {$consultation->duration} minutes");
        $this->command->info("   Ends at: " . $consultation->scheduled_at->addMinutes($consultation->duration)->format('Y-m-d H:i:s'));
        $this->command->info("   Time remaining: ~50 minutes");
        $this->command->info('');
        $this->command->info('👤 Login credentials:');
        $this->command->info('   Client: client@test.com / password');
        $this->command->info('   Lawyer: lawyer@test.com / password');
    }
}
