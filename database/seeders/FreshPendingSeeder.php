<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\User;
use App\Services\DeadlineCalculationService;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FreshPendingSeeder extends Seeder
{
    public function run(): void
    {
        $deadlineService = app(DeadlineCalculationService::class);

        // Get client and lawyer
        $client = User::where('role', 'client')->first();
        $lawyer = User::where('role', 'lawyer')->first();

        if (!$client || !$lawyer) {
            $this->command->error('Client or Lawyer not found. Please run UserSeeder first.');
            return;
        }

        // Delete all existing consultations
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Consultation::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Creating 5 fresh pending consultation requests...');

        // Consultation types and titles
        $consultations = [
            [
                'type' => 'video',
                'title' => 'Employment Contract Review',
                'duration' => 30,
                'notes' => 'I need help reviewing my employment contract. There are some clauses about non-compete agreements that I want to understand better before signing.',
            ],
            [
                'type' => 'chat',
                'title' => 'Property Dispute Advice',
                'duration' => 60,
                'notes' => 'My neighbor is claiming that part of my property belongs to them. I have the land title but they are threatening legal action. What should I do?',
            ],
            [
                'type' => 'video',
                'title' => 'Business Partnership Agreement',
                'duration' => 60,
                'notes' => 'I am starting a business with two partners and we need to draft a partnership agreement. Can you help us understand what should be included?',
            ],
            [
                'type' => 'document_review',
                'title' => 'Lease Agreement Review',
                'duration' => null,
                'notes' => 'I have a commercial lease agreement that I need reviewed. The landlord is asking for a 5-year commitment with annual rent increases. I want to make sure the terms are fair.',
            ],
            [
                'type' => 'chat',
                'title' => 'Labor Law Question',
                'duration' => 15,
                'notes' => 'My employer is asking me to work overtime without proper compensation. Is this legal? What are my rights as an employee?',
            ],
        ];

        foreach ($consultations as $index => $data) {
            // Create scheduled date (tomorrow at different times)
            $scheduledAt = $data['type'] !== 'document_review' 
                ? Carbon::tomorrow()->setTime(9 + $index, 0, 0)
                : null;

            // Calculate rate based on type and duration
            $rate = 0;
            if ($data['type'] === 'video') {
                $rate = match($data['duration']) {
                    15 => $lawyer->lawyerProfile->video_rate_15min,
                    30 => $lawyer->lawyerProfile->video_rate_30min,
                    60 => $lawyer->lawyerProfile->video_rate_60min,
                    default => 1500,
                };
            } elseif ($data['type'] === 'chat') {
                $rate = match($data['duration']) {
                    15 => $lawyer->lawyerProfile->chat_rate_15min,
                    30 => $lawyer->lawyerProfile->chat_rate_30min,
                    60 => $lawyer->lawyerProfile->chat_rate_60min,
                    default => 1000,
                };
            } elseif ($data['type'] === 'document_review') {
                $rate = 0; // Will be quoted by lawyer
            }

            // Create consultation
            $consultation = Consultation::create([
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => $data['type'],
                'title' => $data['title'],
                'duration' => $data['duration'],
                'rate' => $rate,
                'platform_fee' => 0,
                'total_amount' => $rate,
                'status' => 'pending',
                'scheduled_at' => $scheduledAt,
                'client_notes' => $data['notes'],
                'created_at' => now()->subMinutes(10 + $index),
            ]);

            // Calculate lawyer response deadline
            $consultation->lawyer_response_deadline = $deadlineService->calculateLawyerResponseDeadline($consultation);
            $consultation->save();

            $this->command->info("✓ Created: {$data['title']} ({$data['type']})");
        }

        $this->command->info("\n✅ Successfully created 5 pending consultation requests!");
        $this->command->info("Client: {$client->email}");
        $this->command->info("Lawyer: {$lawyer->email}");
    }
}
