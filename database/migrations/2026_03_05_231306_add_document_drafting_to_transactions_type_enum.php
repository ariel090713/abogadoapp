<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, we need to alter the enum column
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('consultation_payment', 'document_drafting', 'refund', 'payout') DEFAULT 'consultation_payment'");
        
        // Also add 'completed' to status enum if not exists
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'held', 'captured', 'completed', 'refunded', 'failed') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('consultation_payment', 'refund', 'payout') DEFAULT 'consultation_payment'");
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'held', 'captured', 'refunded', 'failed') DEFAULT 'pending'");
    }
};
