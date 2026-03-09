<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_knowledge_base', function (Blueprint $table) {
            // Store embeddings as JSON (array of floats)
            $table->json('chunk_embeddings')->nullable()->after('chunks');
            $table->string('embedding_model', 100)->nullable()->after('chunk_embeddings');
        });
    }

    public function down(): void
    {
        Schema::table('ai_knowledge_base', function (Blueprint $table) {
            $table->dropColumn(['chunk_embeddings', 'embedding_model']);
        });
    }
};
