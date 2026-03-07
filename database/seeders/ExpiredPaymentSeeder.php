<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ExpiredPaymentSeeder extends Seeder
{
    /**
     * Create an already-expired payment pending consultation
     */
    public function run(): void
    {
        $client = User::where('email', 'client@test.com')->first();
        $lawyer = User::where('email', 'lawyer@test.com')->first();

        if (!$client || !$lawyer) {
            $this->command->error('Test users not found. Run FreshPaymentPendingSeeder first.');
            return;
        }

        // Create consultation that expired 10 minutes ago
        $consultation = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'EXPIRED: Test Auto-Cancel',
            'duration' => 30,
            'rate' => 500,
            'platform_fee' => 50,
            'total_amount' => 550,
            'status' => 'payment_pending',
            'payment_status' => 'unpaid',
            'scheduled_at' => now()->addDays(1)->setTime(10, 0),
            'accepted_at' => now()->subHour()->subMinutes(10), // Accepted 1 hour 10 minutes ago
            'payment_deadline' => now()->subMinutes(10), // Expired 10 minutes ago!
            'client_notes' => 'This consultation should be auto-cancelled because payment deadline has passed.',
        ]);

        $this->command->info('✅ Created expired consultation:');
        $this->command->info('');
        $this->command->info("ID: {$consultation->id}");
        $this->command->info("Title: {$consultation->title}");
        $this->command->info("Deadline: {$consultation->payment_deadline->format('Y-m-d H:i:s')}");
        $this->command->info("Expired: " . $consultation->payment_deadline->diffForHumans());
        $this->command->info('');
        $this->command->info('Run: php artisan consultations:cancel-expired-payments');
    }
}
