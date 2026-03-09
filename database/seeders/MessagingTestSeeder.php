<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessagingTestSeeder extends Seeder
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

        // Find an active consultation (scheduled or in_progress)
        $consultation = Consultation::where('client_id', $client->id)
            ->where('lawyer_id', $lawyer->id)
            ->whereIn('status', ['scheduled', 'in_progress', 'accepted'])
            ->first();

        if (!$consultation) {
            // Create a test consultation that starts in 2 minutes
            $consultation = Consultation::create([
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'chat',
                'title' => 'Test Chat Consultation',
                'duration' => 60,
                'rate' => 1500.00,
                'total_amount' => 1500.00,
                'status' => 'scheduled',
                'scheduled_at' => now()->addMinutes(2), // Starts in 2 minutes
                'client_notes' => 'Test consultation for messaging system',
            ]);

            // Create transaction for paid consultation
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
                'paymongo_payment_intent_id' => 'pi_test_' . \Illuminate\Support\Str::random(20),
                'processed_at' => now()->subMinutes(5),
            ]);

            $this->command->info("Created test consultation ID: {$consultation->id}");
            $this->command->info("Scheduled for: " . $consultation->scheduled_at->format('Y-m-d H:i:s'));
        }

        // Clear existing messages
        $consultation->messages()->delete();

        // Create sample messages
        $messages = [
            [
                'sender_id' => $client->id,
                'message' => 'Hello Attorney! Thank you for accepting my consultation request.',
                'created_at' => now()->subMinutes(30),
            ],
            [
                'sender_id' => $lawyer->id,
                'message' => 'Hello! You\'re welcome. I\'ve reviewed your case details. How can I help you today?',
                'created_at' => now()->subMinutes(28),
            ],
            [
                'sender_id' => $client->id,
                'message' => 'I need advice regarding a contract dispute with my former employer.',
                'created_at' => now()->subMinutes(25),
            ],
            [
                'sender_id' => $lawyer->id,
                'message' => 'I understand. Can you provide more details about the nature of the dispute?',
                'created_at' => now()->subMinutes(23),
                'read_at' => now()->subMinutes(22),
            ],
            [
                'sender_id' => $client->id,
                'message' => 'They terminated my contract without proper notice and are withholding my final pay.',
                'created_at' => now()->subMinutes(20),
                'read_at' => now()->subMinutes(19),
            ],
            [
                'sender_id' => $lawyer->id,
                'message' => 'That sounds like a potential violation of labor laws. Do you have a copy of your employment contract?',
                'created_at' => now()->subMinutes(18),
                'read_at' => now()->subMinutes(17),
            ],
            [
                'sender_id' => $client->id,
                'message' => 'Yes, I have it. Should I send it to you?',
                'created_at' => now()->subMinutes(15),
                'read_at' => now()->subMinutes(14),
            ],
            [
                'sender_id' => $lawyer->id,
                'message' => 'Yes, please. You can attach it here or we can schedule a document review consultation.',
                'created_at' => now()->subMinutes(12),
            ],
        ];

        foreach ($messages as $messageData) {
            ConsultationMessage::create([
                'consultation_id' => $consultation->id,
                'sender_id' => $messageData['sender_id'],
                'message' => $messageData['message'],
                'attachments' => null,
                'read_at' => $messageData['read_at'] ?? null,
                'created_at' => $messageData['created_at'],
                'updated_at' => $messageData['created_at'],
            ]);
        }

        $this->command->info('✅ Created ' . count($messages) . ' test messages');
        $this->command->info('');
        $this->command->info('📱 Test the chat at:');
        $this->command->info("   Client: /client/consultations/{$consultation->id}/chat");
        $this->command->info("   Lawyer: /lawyer/consultations/{$consultation->id}/chat");
        $this->command->info('');
        $this->command->info('👤 Login credentials:');
        $this->command->info('   Client: client@test.com / password');
        $this->command->info('   Lawyer: lawyer@test.com / password');
    }
}
