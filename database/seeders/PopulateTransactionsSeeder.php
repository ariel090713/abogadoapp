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
        // Find all paid consultations without transactions
        $consultations = Consultation::where('payment_status', 'paid')
            ->whereDoesntHave('transaction')
            ->with(['client', 'lawyer'])
            ->get();

        echo "Found {$consultations->count()} paid consultations without transactions\n";

        foreach ($consultations as $consultation) {
            // Calculate fees
            $platformFee = $consultation->platform_fee ?? ($consultation->total_amount * 0.10);
            $lawyerPayout = $consultation->rate ?? ($consultation->total_amount * 0.90);

            // Create transaction
            $transaction = Transaction::create([
                'consultation_id' => $consultation->id,
                'user_id' => $consultation->client_id,
                'lawyer_id' => $consultation->lawyer_id,
                'type' => 'consultation_payment',
                'amount' => $consultation->total_amount,
                'platform_fee' => $platformFee,
                'lawyer_payout' => $lawyerPayout,
                'status' => 'completed',
                'payment_method' => 'card',
                'processed_at' => $consultation->updated_at,
            ]);

            echo "Created transaction #{$transaction->id} for consultation #{$consultation->id} - Lawyer: {$consultation->lawyer->name}\n";
        }

        echo "\nTransaction Summary:\n";
        echo "Total Transactions: " . Transaction::count() . "\n";
        
        $lawyers = User::where('role', 'lawyer')->get();
        foreach ($lawyers as $lawyer) {
            $count = Transaction::where('lawyer_id', $lawyer->id)->count();
            $total = Transaction::where('lawyer_id', $lawyer->id)->sum('lawyer_payout');
            echo "- {$lawyer->name}: {$count} transactions, ₱" . number_format($total, 2) . " earnings\n";
        }
    }
}
