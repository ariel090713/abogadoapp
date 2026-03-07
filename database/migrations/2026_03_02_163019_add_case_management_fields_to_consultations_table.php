<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Parent-child relationship for follow-up sessions
            $table->unsignedBigInteger('parent_consultation_id')->nullable()->after('id');
            
            // Case management
            $table->string('case_number')->nullable()->unique()->after('parent_consultation_id');
            $table->integer('session_number')->default(1)->after('case_number');
            $table->boolean('is_follow_up')->default(false)->after('session_number');
            $table->string('follow_up_type')->nullable()->after('is_follow_up'); // additional_documents, clarification, new_issue, revision
            
            // Case status (only for parent consultations)
            $table->string('case_status')->nullable()->after('status'); // active, on_hold, closed
            $table->timestamp('case_closed_at')->nullable()->after('completed_at');
            $table->text('case_closure_notes')->nullable()->after('case_closed_at');
            
            // Foreign key
            $table->foreign('parent_consultation_id')->references('id')->on('consultations')->onDelete('cascade');
            
            // Indexes for better query performance
            $table->index('case_number');
            $table->index('parent_consultation_id');
            $table->index('case_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['parent_consultation_id']);
            $table->dropIndex(['case_number']);
            $table->dropIndex(['parent_consultation_id']);
            $table->dropIndex(['case_status']);
            
            $table->dropColumn([
                'parent_consultation_id',
                'case_number',
                'session_number',
                'is_follow_up',
                'follow_up_type',
                'case_status',
                'case_closed_at',
                'case_closure_notes',
            ]);
        });
    }
};
