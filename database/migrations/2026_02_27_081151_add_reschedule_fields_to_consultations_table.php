<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Reschedule tracking
            $table->timestamp('original_scheduled_at')->nullable()->after('scheduled_at');
            $table->string('reschedule_status')->nullable()->after('status'); // null, 'pending', 'approved', 'declined'
            $table->unsignedBigInteger('reschedule_requested_by')->nullable()->after('reschedule_status');
            $table->timestamp('reschedule_requested_at')->nullable()->after('reschedule_requested_by');
            $table->timestamp('proposed_scheduled_at')->nullable()->after('reschedule_requested_at');
            $table->text('reschedule_reason')->nullable()->after('proposed_scheduled_at');
            $table->text('reschedule_decline_reason')->nullable()->after('reschedule_reason');
            $table->integer('reschedule_count')->default(0)->after('reschedule_decline_reason');
            
            $table->foreign('reschedule_requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['reschedule_requested_by']);
            $table->dropColumn([
                'original_scheduled_at',
                'reschedule_status',
                'reschedule_requested_by',
                'reschedule_requested_at',
                'proposed_scheduled_at',
                'reschedule_reason',
                'reschedule_decline_reason',
                'reschedule_count',
            ]);
        });
    }
};
