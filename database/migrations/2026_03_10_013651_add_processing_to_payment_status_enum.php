<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add 'processing' to payment_status enum
        DB::statement("ALTER TABLE consultations MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'processing', 'paid', 'refunded', 'free', 'failed') DEFAULT 'unpaid'");
    }

    public function down()
    {
        // Revert back to previous enum values
        DB::statement("ALTER TABLE consultations MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'refunded', 'free') DEFAULT 'unpaid'");
    }
};
