<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('document_request_id')->nullable()->after('consultation_id')->constrained('document_drafting_requests')->onDelete('cascade');
            
            // Make consultation_id nullable since transactions can be for either consultations or documents
            $table->foreignId('consultation_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['document_request_id']);
            $table->dropColumn('document_request_id');
        });
    }
};
