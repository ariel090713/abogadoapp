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
            $table->string('reviewed_document_deleted_path')->nullable()->after('reviewed_document_path');
            $table->dateTime('reviewed_document_deleted_at')->nullable()->after('reviewed_document_deleted_path');
            $table->dateTime('completion_updated_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['reviewed_document_deleted_path', 'reviewed_document_deleted_at', 'completion_updated_at']);
        });
    }
};
