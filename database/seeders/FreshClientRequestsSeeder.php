<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\User;
use App\Services\DeadlineCalculationService;
use Illuminate\Database\Seeder;

class FreshClientRequestsSeeder extends Seeder
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

        $this->command->info('Creating 5 fresh consultation requests...');

        // 1. PENDING - Lawyer needs to respond (Video Call - 6 hours from now)
        $pending1 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Contract Dispute Consultation',
            'duration' => 30,
            'rate' => 1500.00,
            'platform_fee' => 150.00,
            'total_amount' => 1650.00,
            'status' => 'pending',
            'scheduled_at' => now()->addHours(6),
            'client_notes' => 'I need urgent advice regarding a contract dispute with my business partner.',
            'created_at' => now()->subMinutes(15),
        ]);
        $pending1->lawyer_response_deadline = $deadlineService->calculateLawyerResponseDeadline($pending1);
        $pending1->save();
        $this->command->info("✓ Created PENDING consultation (ID: {$pending1->id})");
        $this->command->info("  - Scheduled: {$pending1->scheduled_at->format('M d, Y g:i A')}");
        $this->command->info("  - Lawyer must respond by: {$pending1->lawyer_response_deadline->format('M d, Y g:i A')}");

        // 2. AWAITING QUOTE - Client needs to respond to quote (Document Review)
        $awaitingQuote = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'document_review',
            'title' => 'Employment Contract Review',
            'rate' => 3000.00,
            'quoted_price' => 3000.00,
            'quote_notes' => 'I can review your employment contract thoroughly within 2 business days. The review will include analysis of all clauses, identification of potential issues, and recommendations.',
            'quote_provided_at' => now()->subHours(2),
            'platform_fee' => 300.00,
            'total_amount' => 3300.00,
            'status' => 'awaiting_quote_approval',
            'client_notes' => 'Please review my employment contract before I sign it.',
            'created_at' => now()->subHours(4),
        ]);
        $awaitingQuote->quote_deadline = $deadlineService->calculateQuoteResponseDeadline($awaitingQuote);
        $awaitingQuote->save();
        $this->command->info("✓ Created AWAITING QUOTE consultation (ID: {$awaitingQuote->id})");
        $this->command->info("  - Quote provided: {$awaitingQuote->quote_provided_at->format('M d, Y g:i A')}");
        $this->command->info("  - Client must respond by: {$awaitingQuote->quote_deadline->format('M d, Y g:i A')}");

        // 3. PAYMENT PENDING - Client needs to pay (Video Call - 8 hours from now)
        $paymentPending = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Real Estate Legal Advice',
            'duration' => 60,
            'rate' => 2500.00,
            'platform_fee' => 250.00,
            'total_amount' => 2750.00,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addHours(8),
            'accepted_at' => now()->subMinutes(45),
            'quote_accepted_at' => now()->subMinutes(45),
            'client_notes' => 'Need advice on property purchase agreement and potential legal issues.',
            'created_at' => now()->subHours(1),
        ]);
        $paymentPending->payment_deadline = $deadlineService->calculatePaymentDeadline($paymentPending);
        $paymentPending->payment_deadline_calculated = $paymentPending->payment_deadline;
        $paymentPending->save();
        $this->command->info("✓ Created PAYMENT PENDING consultation (ID: {$paymentPending->id})");
        $this->command->info("  - Scheduled: {$paymentPending->scheduled_at->format('M d, Y g:i A')}");
        $this->command->info("  - Payment deadline: {$paymentPending->payment_deadline->format('M d, Y g:i A')}");

        // 4. URGENT PENDING - Very close to session time (4 hours from now)
        $urgentPending = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'chat',
            'title' => 'Urgent: Tenant Rights Question',
            'duration' => 15,
            'rate' => 800.00,
            'platform_fee' => 80.00,
            'total_amount' => 880.00,
            'status' => 'pending',
            'scheduled_at' => now()->addHours(4),
            'client_notes' => 'Landlord is threatening eviction. Need immediate legal advice.',
            'created_at' => now()->subMinutes(5),
        ]);
        $urgentPending->lawyer_response_deadline = $deadlineService->calculateLawyerResponseDeadline($urgentPending);
        $urgentPending->save();
        $this->command->info("✓ Created URGENT PENDING consultation (ID: {$urgentPending->id})");
        $this->command->info("  - Scheduled: {$urgentPending->scheduled_at->format('M d, Y g:i A')}");
        $this->command->info("  - Lawyer must respond by: {$urgentPending->lawyer_response_deadline->format('M d, Y g:i A')} (URGENT!)");

        // 5. PAYMENT PENDING - About to expire (30 minutes left)
        $aboutToExpire = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'consultation_type' => 'video',
            'title' => 'Family Law Consultation',
            'duration' => 30,
            'rate' => 1800.00,
            'platform_fee' => 180.00,
            'total_amount' => 1980.00,
            'status' => 'payment_pending',
            'scheduled_at' => now()->addHours(2),
            'accepted_at' => now()->subHours(23)->subMinutes(30),
            'quote_accepted_at' => now()->subHours(23)->subMinutes(30),
            'payment_deadline' => now()->addMinutes(30),
            'payment_deadline_calculated' => now()->addMinutes(30),
            'client_notes' => 'Need advice on child custody arrangements.',
            'created_at' => now()->subDay(),
        ]);
        $aboutToExpire->save();
        $this->command->info("✓ Created ABOUT TO EXPIRE consultation (ID: {$aboutToExpire->id})");
        $this->command->info("  - Scheduled: {$aboutToExpire->scheduled_at->format('M d, Y g:i A')}");
        $this->command->info("  - Payment deadline: {$aboutToExpire->payment_deadline->format('M d, Y g:i A')} (30 MINUTES!)");

        $this->command->info("\n✅ Created 5 fresh consultation requests!");
        $this->command->info("\n📊 Summary:");
        $this->command->info("  - 2 PENDING (lawyer needs to respond)");
        $this->command->info("  - 1 AWAITING QUOTE (client needs to respond)");
        $this->command->info("  - 2 PAYMENT PENDING (client needs to pay)");
        $this->command->info("\n🔔 Urgency Levels:");
        $this->command->info("  - 1 consultation expires in 30 minutes");
        $this->command->info("  - 1 urgent consultation (4 hours until session)");
        $this->command->info("  - 3 normal consultations");
        $this->command->info("\n👉 Login as client@test.com to see countdown timers!");
    }
}
