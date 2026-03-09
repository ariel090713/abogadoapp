<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'payment_processing' to status enum
        // This status is used when user returns from PayMongo but webhook hasn't confirmed payment yet
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM(
            'pending',
            'awaiting_quote_approval',
            'accepted',
            'declined',
            'scheduled',
            'in_progress',
            'completed',
            'cancelled',
            'ended',
            'payment_pending',
            'payment_processing',
            'payment_failed',
            'expired',
            'pending_client_acceptance'
        ) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'payment_processing' from status enum
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM(
            'pending',
            'awaiting_quote_approval',
            'accepted',
            'declined',
            'scheduled',
            'in_progress',
            'completed',
            'cancelled',
            'ended',
            'payment_pending',
            'payment_failed',
            'expired',
            'pending_client_acceptance'
        ) NOT NULL DEFAULT 'pending'");
    }
};
