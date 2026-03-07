<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->timestamp('lawyer_response_deadline')->nullable()->after('payment_deadline');
            $table->timestamp('quote_deadline')->nullable()->after('lawyer_response_deadline');
            $table->timestamp('payment_deadline_calculated')->nullable()->after('quote_deadline');
            $table->timestamp('review_completion_deadline')->nullable()->after('payment_deadline_calculated');
            $table->integer('estimated_turnaround_days')->nullable()->after('review_completion_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'lawyer_response_deadline',
                'quote_deadline',
                'payment_deadline_calculated',
                'review_completion_deadline',
                'estimated_turnaround_days',
            ]);
        });
    }
};
