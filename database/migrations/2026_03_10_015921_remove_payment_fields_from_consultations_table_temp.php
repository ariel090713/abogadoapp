<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * TEMPORARY MIGRATION: Remove payment fields to test refactoring
     * This will help identify all code that needs to be updated
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_intent_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->enum('payment_status', [
                'unpaid', 'pending', 'processing', 'paid', 'refunded', 'free', 'failed'
            ])->default('unpaid')->after('status');
            
            $table->string('payment_intent_id')->nullable()->after('payment_status');
        });
    }
};
