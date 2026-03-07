<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lawyer_document_services', function (Blueprint $table) {
            $table->string('category')->after('template_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('lawyer_document_services', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
