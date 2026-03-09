<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LawyerInitiatedServiceSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('email', 'client@test.com')->first();
        $lawyer = User::where('email', 'lawyer@test.com')->first();

        if (!$client || !$lawyer) {
            $this->command->error('Test users not found. Run UserSeeder first.');
            return;
        }

        // Find an existing case or create one
        $parentCase = Consultation::where('client_id', $client->id)
            ->where('lawyer_id', $lawyer->id)
            ->whereNull('parent_consultation_id')
            ->first();

        if (!$parentCase) {
            $paymentIntentId = 'pi_test_' . \Illuminate\Support\Str::random(20);
            $parentCase = Consultation::create([
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'consultation_type' => 'chat',
                'title' => 'Contract Review Case',
                'duration' => 30,
                'rate' => 1500,
                'total_amount' => 1500,
                'status' => 'completed',
                'scheduled_at' => Carbon::now()->subDays(5),
                'accepted_at' => Carbon::now()->subDays(5),
                'started_at' => Carbon::now()->subDays(5),
                'ended_at' => Carbon::now()->subDays(5)->addMinutes(30),
                'completed_at' => Carbon::now()->subDays(5)->addMinutes(30),
                'client_notes' => 'Need help reviewing employment contract',
                'initiated_by' => 'client',
            ]);

            \App\Models\Transaction::create([
                'consultation_id' => $parentCase->id,
                'user_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'type' => 'consultation_payment',
                'amount' => 1500,
                'lawyer_payout' => 1500,
                'platform_fee' => 0,
                'status' => 'completed',
                'payment_method' => 'gcash',
                'paymongo_payment_intent_id' => $paymentIntentId,
                'processed_at' => Carbon::now()->subDays(5),
            ]);
            
            $this->command->info('Created parent case: ' . $parentCase->getCaseNumber());
        }

        // Scenario 1: Free chat consultation offer (pending)
        $offer1 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'parent_consultation_id' => $parentCase->id,
            'initiated_by' => 'lawyer',
            'consultation_type' => 'chat',
            'title' => 'Follow-up Discussion - Free',
            'duration' => 15,
            'rate' => 0,
            'quoted_price' => 0,
            'quote_notes' => 'I would like to offer a free 15-minute follow-up to discuss any remaining questions you may have about your employment contract.',
            'total_amount' => 0,
            'status' => 'pending_client_acceptance',
            'scheduled_at' => Carbon::now()->addDays(2)->setTime(14, 0),
            'quote_provided_at' => Carbon::now()->subHours(2),
        ]);

        // Create free transaction
        \App\Models\Transaction::create([
            'consultation_id' => $offer1->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 0,
            'lawyer_payout' => 0,
            'platform_fee' => 0,
            'status' => 'completed',
            'payment_method' => 'free',
            'processed_at' => Carbon::now()->subHours(2),
        ]);

        $this->command->info('Created free chat offer (pending): ' . $offer1->id);

        // Scenario 2: Paid video consultation offer (pending)
        $offer2 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'parent_consultation_id' => $parentCase->id,
            'initiated_by' => 'lawyer',
            'consultation_type' => 'video',
            'title' => 'Detailed Contract Negotiation Strategy',
            'duration' => 60,
            'rate' => 3000,
            'quoted_price' => 3000,
            'quote_notes' => 'Based on our previous discussion, I recommend a comprehensive video consultation to develop a negotiation strategy for your contract terms. This will include detailed analysis and actionable recommendations.',
            'total_amount' => 3000,
            'status' => 'pending_client_acceptance',
            'scheduled_at' => Carbon::now()->addDays(3)->setTime(10, 0),
            'quote_provided_at' => Carbon::now()->subHours(1),
        ]);

        // Create pending transaction
        \App\Models\Transaction::create([
            'consultation_id' => $offer2->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 3000,
            'lawyer_payout' => 3000,
            'platform_fee' => 0,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        $this->command->info('Created paid video offer (pending): ' . $offer2->id);

        // Scenario 3: Document review offer (pending)
        $offer3 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'parent_consultation_id' => $parentCase->id,
            'initiated_by' => 'lawyer',
            'consultation_type' => 'document_review',
            'title' => 'Revised Contract Review',
            'rate' => 2500,
            'quoted_price' => 2500,
            'quote_notes' => 'I can review the revised version of your employment contract and provide detailed feedback on the changes. This will include markup and recommendations.',
            'total_amount' => 2500,
            'status' => 'pending_client_acceptance',
            'quote_provided_at' => Carbon::now()->subMinutes(30),
        ]);

        // Create pending transaction
        \App\Models\Transaction::create([
            'consultation_id' => $offer3->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 2500,
            'lawyer_payout' => 2500,
            'platform_fee' => 0,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        $this->command->info('Created document review offer (pending): ' . $offer3->id);

        // Scenario 4: Accepted free service (now active)
        $offer4 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'parent_consultation_id' => $parentCase->id,
            'initiated_by' => 'lawyer',
            'consultation_type' => 'chat',
            'title' => 'Quick Clarification - Free',
            'duration' => 15,
            'rate' => 0,
            'quoted_price' => 0,
            'quote_notes' => 'Free quick chat to clarify the termination clause.',
            'total_amount' => 0,
            'status' => 'scheduled',
            'scheduled_at' => Carbon::now()->addDays(1)->setTime(15, 0),
            'quote_provided_at' => Carbon::now()->subDays(1),
            'quote_accepted_at' => Carbon::now()->subHours(3),
            'accepted_at' => Carbon::now()->subHours(3),
        ]);

        // Create free transaction
        \App\Models\Transaction::create([
            'consultation_id' => $offer4->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 0,
            'lawyer_payout' => 0,
            'platform_fee' => 0,
            'status' => 'completed',
            'payment_method' => 'free',
            'processed_at' => Carbon::now()->subHours(3),
        ]);

        $this->command->info('Created accepted free service (scheduled): ' . $offer4->id);

        // Scenario 5: Declined offer
        $offer5 = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'parent_consultation_id' => $parentCase->id,
            'initiated_by' => 'lawyer',
            'consultation_type' => 'video',
            'title' => 'Extended Consultation Session',
            'duration' => 60,
            'rate' => 4000,
            'quoted_price' => 4000,
            'quote_notes' => 'Extended session for comprehensive review.',
            'total_amount' => 4000,
            'status' => 'declined',
            'scheduled_at' => Carbon::now()->addDays(4)->setTime(11, 0),
            'quote_provided_at' => Carbon::now()->subDays(2),
        ]);

        // Create pending transaction (declined before payment)
        \App\Models\Transaction::create([
            'consultation_id' => $offer5->id,
            'user_id' => $client->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'consultation_payment',
            'amount' => 4000,
            'lawyer_payout' => 4000,
            'platform_fee' => 0,
            'status' => 'pending',
            'payment_method' => null,
        ]);

        $this->command->info('Created declined offer: ' . $offer5->id);

        $this->command->info('✅ Lawyer-initiated service test data created successfully!');
        $this->command->info('Parent Case ID: ' . $parentCase->id);
        $this->command->info('View as client: https://AbogadoMoapp.test/client/cases/' . $parentCase->id);
        $this->command->info('View as lawyer: https://AbogadoMoapp.test/lawyer/cases/' . $parentCase->id);
    }
}
