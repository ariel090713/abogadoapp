<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class PayoutTestSeeder extends Seeder
{
    /**
     * Seed transactions that are eligible for payout
     * (completed, 7+ days old, no payout_id, no refund_id)
     */
    public function run(): void
    {
        $this->command->info('Creating payout-eligible transactions...');

        // Get lawyers
        $lawyers = User::where('role', 'lawyer')
            ->whereHas('lawyerProfile')
            ->take(5)
            ->get();

        if ($lawyers->isEmpty()) {
            $this->command->error('No lawyers found. Run user seeder first.');
            return;
        }

        // Get some clients
        $clients = User::where('role', 'client')->take(10)->get();

        if ($clients->isEmpty()) {
            $this->command->error('No clients found. Run user seeder first.');
            return;
        }

        $transactionCount = 0;

        foreach ($lawyers as $lawyer) {
            // Create 3-8 transactions per lawyer (random)
            $numTransactions = rand(3, 8);
            
            for ($i = 0; $i < $numTransactions; $i++) {
                $client = $clients->random();
                $amount = rand(1000, 5000); // ₱1,000 to ₱5,000
                $paymentIntentId = 'pi_test_' . uniqid();
                
                // Create consultation
                $consultation = Consultation::create([
                    'client_id' => $client->id,
                    'lawyer_id' => $lawyer->id,
                    'consultation_type' => ['video', 'chat', 'document_review'][rand(0, 2)],
                    'status' => 'completed',
                    'total_amount' => $amount,
                    'platform_fee' => 0,
                    'rate' => $amount, // Hourly/service rate
                    'scheduled_at' => now()->subDays(rand(8, 30)),
                    'started_at' => now()->subDays(rand(8, 30)),
                    'completed_at' => now()->subDays(rand(8, 30)),
                ]);

                // Create transaction (8-30 days old = eligible for payout)
                $transaction = Transaction::create([
                    'consultation_id' => $consultation->id,
                    'user_id' => $client->id,
                    'lawyer_id' => $lawyer->id,
                    'type' => 'consultation_payment',
                    'amount' => $amount,
                    'platform_fee' => 0,
                    'lawyer_payout' => $amount,
                    'status' => 'completed',
                    'payment_method' => ['card', 'gcash', 'paymaya'][rand(0, 2)],
                    'paymongo_payment_intent_id' => $paymentIntentId,
                    'paymongo_payment_id' => 'pay_test_' . uniqid(),
                    'reference_number' => 'TXN-' . strtoupper(uniqid()),
                    'processed_at' => now()->subDays(rand(8, 30)),
                    'created_at' => now()->subDays(rand(8, 30)),
                ]);

                $transactionCount++;
            }
        }

        $this->command->info("✅ Created {$transactionCount} payout-eligible transactions for {$lawyers->count()} lawyers");
        
        // Show summary
        $this->command->newLine();
        $this->command->info('Summary per lawyer:');
        foreach ($lawyers as $lawyer) {
            $transactions = Transaction::where('lawyer_id', $lawyer->id)
                ->where('status', 'completed')
                ->whereNull('payout_id')
                ->whereNull('refund_id')
                ->where('created_at', '<=', now()->subDays(7))
                ->get();
            
            $total = $transactions->sum('lawyer_payout');
            $count = $transactions->count();
            
            $this->command->line("  • {$lawyer->name}: ₱" . number_format($total, 2) . " ({$count} transactions)");
        }
        
        $this->command->newLine();
        $this->command->info('Now go to Admin → Payouts to create payout batch!');
        
        // Optional: Create some sample completed payouts
        $this->command->newLine();
        $this->command->ask('Create sample completed payouts? (yes/no)', 'yes');
        
        if ($this->command->confirm('Create sample payouts?', true)) {
            $this->createSamplePayouts($lawyers);
        }
    }
    
    private function createSamplePayouts($lawyers)
    {
        $this->command->info('Creating sample payouts...');
        
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->error('No admin found');
            return;
        }
        
        // Create 2-3 completed payouts from past
        $payoutCount = 0;
        foreach ($lawyers->take(3) as $lawyer) {
            // Get some old transactions (30-60 days old)
            $oldTransactions = Transaction::where('lawyer_id', $lawyer->id)
                ->where('status', 'completed')
                ->whereNull('payout_id')
                ->whereNull('refund_id')
                ->where('created_at', '<=', now()->subDays(30))
                ->take(rand(2, 4))
                ->get();
            
            if ($oldTransactions->isEmpty()) {
                continue;
            }
            
            $totalAmount = $oldTransactions->sum('lawyer_payout');
            
            // Create payout
            $payout = \App\Models\Payout::create([
                'lawyer_id' => $lawyer->id,
                'amount' => $totalAmount,
                'status' => 'completed',
                'method' => ['bank_transfer', 'gcash', 'paymaya'][rand(0, 2)],
                'reference_number' => 'REF-' . strtoupper(uniqid()),
                'notes' => 'Sample completed payout',
                'processed_by' => $admin->id,
                'processed_at' => now()->subDays(rand(1, 15)),
                'created_at' => now()->subDays(rand(15, 25)),
            ]);
            
            // Link transactions
            $oldTransactions->each(function ($transaction) use ($payout) {
                $transaction->update(['payout_id' => $payout->id]);
            });
            
            $payoutCount++;
            $this->command->line("  • Created payout for {$lawyer->name}: ₱" . number_format($totalAmount, 2));
        }
        
        $this->command->info("✅ Created {$payoutCount} sample completed payouts");
    }
}
