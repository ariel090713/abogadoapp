<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\User;
use App\Services\DeadlineCalculationService;
use Illuminate\Database\Seeder;

class DeadlineTestSeeder extends Seeder
{
    public function run()
    {
        $deadlineService = app(DeadlineCalculationService::class);
        
        // Get test users
        $client = User::where('email', 'client@test.com')->first();
        $lawyer = User::where('email', 'lawyer@test.com')->first();

        if (!$client || !$lawyer) {
            $this->command->error('Test users not found. Run UserSeeder first.');
            return;
        }

        $this->command->info('Creating consultations with deadlines...');

        // 1. Pending consultation - lawyer needs to respond
        $pending = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Urgent Legal Advice Needed',
            'duration' => 30,
            'rate' => 1500.00,
            'platform_fee' => 150.00,
            'total_amount' => 1650.00,
            'status' => 'pending',
            'scheduled_at' => now()->addHours(6),
            'client_notes' => 'Need urgent advice on contract review.',
            'created_at' => now()->subHours(2),
        ]);
        $pending->lawyer_response_deadline = $deadlineService->calculateLawyerResponseDeadline($pending);
        $pending->save();
        $this->command->info("✓ Created pending consultation (ID: {$pending->id}) - Lawyer response deadline: {$pending->lawyer_response_deadline->format('M d, Y g:i A')}");

        // 2. Awaiting quote approval - client needs to respond
        $awaitingQuote = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'document_review',
            'title' => 'Contract Review Service',
            'rate' => 2500.00,
            'quoted_price' => 2500.00,
            'quote_notes' => 'I can review your contract within 2 business days.',
            'quote_provided_at' => now()->subHours(1),
            'platform_fee' => 250.00,
            'total_amount' => 2750.00,
            'status' => 'awaiting_quote_approval',
            'client_notes' => 'Need comprehensive contract review.',
            'created_at' => now()->subHours(3),
        ]);
        $awaitingQuote->quote_deadline = $deadlineService->calculateQuoteResponseDeadline($awaitingQuote);
        $awaitingQuote->save();
        $this->command->info("✓ Created awaiting quote consultation (ID: {$awaitingQuote->id}) - Quote deadline: {$awaitingQuote->quote_deadline->format('M d, Y g:i A')}");

        // 3. Payment pending - client needs to pay
        $paymentPending = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Business Law Consultation',
            'duration' => 60,
            'rate' => 3000.00,
            'platform_fee' => 300.00,
            'total_amount' => 3300.00,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addHours(8),
            'accepted_at' => now()->subMinutes(30),
            'quote_accepted_at' => now()->subMinutes(30),
            'client_notes' => 'Business partnership agreement discussion.',
            'created_at' => now()->subHours(4),
        ]);
        $paymentPending->payment_deadline = $deadlineService->calculatePaymentDeadline($paymentPending);
        $paymentPending->payment_deadline_calculated = $paymentPending->payment_deadline;
        $paymentPending->save();
        $this->command->info("✓ Created payment pending consultation (ID: {$paymentPending->id}) - Payment deadline: {$paymentPending->payment_deadline->format('M d, Y g:i A')}");

        // 4. Urgent pending - very close to session time
        $urgentPending = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Emergency Legal Consultation',
            'duration' => 30,
            'rate' => 2000.00,
            'platform_fee' => 200.00,
            'total_amount' => 2200.00,
            'status' => 'pending',
            'scheduled_at' => now()->addHours(4),
            'client_notes' => 'Urgent matter - need immediate consultation.',
            'created_at' => now()->subMinutes(30),
        ]);
        $urgentPending->lawyer_response_deadline = $deadlineService->calculateLawyerResponseDeadline($urgentPending);
        $urgentPending->save();
        $this->command->info("✓ Created urgent pending consultation (ID: {$urgentPending->id}) - Lawyer response deadline: {$urgentPending->lawyer_response_deadline->format('M d, Y g:i A')}");

        // 5. About to expire - payment deadline in 30 minutes
        $aboutToExpire = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Quick Legal Question',
            'duration' => 15,
            'rate' => 500.00,
            'platform_fee' => 50.00,
            'total_amount' => 550.00,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addHours(2),
            'accepted_at' => now()->subHours(23)->subMinutes(30),
            'quote_accepted_at' => now()->subHours(23)->subMinutes(30),
            'payment_deadline' => now()->addMinutes(30),
            'payment_deadline_calculated' => now()->addMinutes(30),
            'client_notes' => 'Quick question about tenant rights.',
            'created_at' => now()->subDay(),
        ]);
        $aboutToExpire->save();
        $this->command->info("✓ Created about-to-expire consultation (ID: {$aboutToExpire->id}) - Payment deadline: {$aboutToExpire->payment_deadline->format('M d, Y g:i A')}");

        $this->command->info("\n✅ Deadline test data created successfully!");
        $this->command->info("\nTest the countdown timers by:");
        $this->command->info("1. Login as client@test.com to see payment and quote deadlines");
        $this->command->info("2. Login as lawyer@test.com to see lawyer response deadlines");
        $this->command->info("3. Run 'php artisan consultations:expire-deadlines' to test expiration");
    }
}
