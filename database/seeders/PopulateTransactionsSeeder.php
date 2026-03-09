<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultation;
use App\Models\Transaction;
use App\Models\User;

class PopulateTransactionsSeeder extends Seeder
{
    public function run(): void
    {
        // Find all consultations with completed transactions but no transaction record
        $consultations = Consultation::whereHas('transaction', function($q) {
                $q->where('status', 'completed');
            })
            ->orWhere(function($q) {
                // Or consultations that should have transactions but don't
                $q->whereIn('status', ['completed', 'scheduled', 'in_progress'])
                  ->whereDoesntHave('transaction');
            })
            ->with(['client', 'lawyer', 'transaction'])
            ->get();

        echo "Found {$consultations->count()} consultations to check\n";

        $created = 0;
        foreach ($consultations as $consultation) {
            // Skip if already has transaction
            if ($consultation->transaction) {
                continue;
            }

            // Calculate fees
            $platformFee = $consultation->platform_fee ?? ($consultation->total_amount * 0.10);
            $lawyerPayout = $consultation->rate ?? ($consultation->total_amount * 0.90);

            // Determine status based on consultation status
            $status = in_array($consultation->status, ['completed', 'scheduled', 'in_progress']) 
                ? 'completed' 
                : 'pending';

            // Create transaction
            $transaction = Transaction::create([
                'consultation_id' => $consultation->id,
                'user_id' => $consultation->client_id,
                'lawyer_id' => $consultation->lawyer_id,
                'type' => 'consultation_payment',
                'amount' => $consultation->total_amount,
                'platform_fee' => $platformFee,
                'lawyer_payout' => $lawyerPayout,
                'status' => $status,
                'payment_method' => 'card',
                'processed_at' => $status === 'completed' ? $consultation->updated_at : null,
            ]);

            $created++;
            echo "Created transaction #{$transaction->id} for consultation #{$consultation->id} - Lawyer: {$consultation->lawyer->name}\n";
        }

        echo "\nTransaction Summary:\n";
        echo "Created: {$created} transactions\n";
        echo "Total Transactions: " . Transaction::count() . "\n";
        
        $lawyers = User::where('role', 'lawyer')->get();
        foreach ($lawyers as $lawyer) {
            $count = Transaction::where('lawyer_id', $lawyer->id)->count();
            $total = Transaction::where('lawyer_id', $lawyer->id)->sum('lawyer_payout');
            echo "- {$lawyer->name}: {$count} transactions, ₱" . number_format($total, 2) . " earnings\n";
        }
    }
}
