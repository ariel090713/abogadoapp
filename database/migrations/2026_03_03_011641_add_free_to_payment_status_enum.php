<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, we need to alter the enum column
        DB::statement("ALTER TABLE consultations MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'refunded', 'free') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE consultations MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'refunded') DEFAULT 'unpaid'");
    }
};
