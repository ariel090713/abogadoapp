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
        // Add revisions_allowed to lawyer_document_services
        Schema::table('lawyer_document_services', function (Blueprint $table) {
            $table->integer('revisions_allowed')->default(1)->after('estimated_completion_days');
        });

        // Add revision tracking to document_drafting_requests
        Schema::table('document_drafting_requests', function (Blueprint $table) {
            $table->integer('revisions_used')->default(0)->after('status');
            $table->integer('revisions_allowed')->default(1)->after('revisions_used');
            $table->text('revision_notes')->nullable()->after('revisions_allowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lawyer_document_services', function (Blueprint $table) {
            $table->dropColumn('revisions_allowed');
        });

        Schema::table('document_drafting_requests', function (Blueprint $table) {
            $table->dropColumn(['revisions_used', 'revisions_allowed', 'revision_notes']);
        });
    }
};
