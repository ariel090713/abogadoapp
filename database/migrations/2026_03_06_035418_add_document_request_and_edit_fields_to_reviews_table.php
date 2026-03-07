<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('document_request_id')->nullable()->after('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_request_id')->nullable()->after('document_request_id')->constrained()->onDelete('cascade');
            $table->boolean('is_edited')->default(false)->after('comment');
            $table->timestamp('edited_at')->nullable()->after('is_edited');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['document_request_id']);
            $table->dropForeign(['service_request_id']);
            $table->dropColumn(['document_request_id', 'service_request_id', 'is_edited', 'edited_at']);
        });
    }
};
