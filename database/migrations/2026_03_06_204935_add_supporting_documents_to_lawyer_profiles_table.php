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
        Schema::table('lawyer_profiles', function (Blueprint $table) {
            $table->string('ibp_card_path')->nullable()->after('ibp_number');
            $table->string('supporting_document_path')->nullable()->after('ibp_card_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lawyer_profiles', function (Blueprint $table) {
            $table->dropColumn(['ibp_card_path', 'supporting_document_path']);
        });
    }
};
