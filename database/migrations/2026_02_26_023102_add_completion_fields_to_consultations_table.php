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
            $table->string('reviewed_document_path')->nullable()->after('document_path');
            $table->text('completion_notes')->nullable()->after('lawyer_notes');
            $table->dateTime('completed_at')->nullable()->after('ended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['reviewed_document_path', 'completion_notes', 'completed_at']);
        });
    }
};
