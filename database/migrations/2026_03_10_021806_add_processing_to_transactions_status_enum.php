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
        // Add 'processing' to transactions status enum
        // This status is used when user returns from PayMongo but webhook hasn't confirmed payment yet
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM(
            'pending',
            'held',
            'captured',
            'processing',
            'completed',
            'refunded',
            'failed'
        ) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'processing' from transactions status enum
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM(
            'pending',
            'held',
            'captured',
            'completed',
            'refunded',
            'failed'
        ) DEFAULT 'pending'");
    }
};
