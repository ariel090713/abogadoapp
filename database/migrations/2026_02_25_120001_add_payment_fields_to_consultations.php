<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            if (!Schema::hasColumn('consultations', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'refunded'])->default('unpaid')->after('status');
            }
            if (!Schema::hasColumn('consultations', 'payment_intent_id')) {
                $table->string('payment_intent_id')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_intent_id']);
        });
    }
};
