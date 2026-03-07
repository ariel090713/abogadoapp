<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('pending', 'awaiting_quote_approval', 'accepted', 'declined', 'scheduled', 'in_progress', 'completed', 'cancelled', 'ended', 'payment_pending', 'payment_failed', 'expired', 'pending_client_acceptance') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('pending', 'awaiting_quote_approval', 'accepted', 'declined', 'scheduled', 'in_progress', 'completed', 'cancelled', 'ended', 'payment_pending', 'payment_failed', 'expired') NOT NULL DEFAULT 'pending'");
    }
};
