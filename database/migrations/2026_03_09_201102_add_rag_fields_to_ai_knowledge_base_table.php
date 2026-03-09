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
        Schema::table('ai_knowledge_base', function (Blueprint $table) {
            // File metadata
            $table->string('mime_type')->nullable()->after('file_name');
            $table->string('content_hash')->nullable()->after('mime_type'); // SHA-256 hash for deduplication
            
            // Chunking for RAG
            $table->json('chunks')->nullable()->after('content'); // Store text chunks as JSON array
            $table->integer('chunk_size')->default(1000)->after('chunks'); // Characters per chunk
            $table->integer('chunk_overlap')->default(200)->after('chunk_size'); // Overlap between chunks
            
            // Additional metadata
            $table->json('metadata')->nullable()->after('priority'); // Store additional info (author, source, tags, etc.)
            $table->timestamp('processed_at')->nullable()->after('metadata'); // When the document was processed/chunked
            
            // Indexing for better performance
            $table->index('content_hash');
            $table->index('mime_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_knowledge_base', function (Blueprint $table) {
            $table->dropIndex(['content_hash']);
            $table->dropIndex(['mime_type']);
            $table->dropIndex(['is_active']);
            
            $table->dropColumn([
                'mime_type',
                'content_hash',
                'chunks',
                'chunk_size',
                'chunk_overlap',
                'metadata',
                'processed_at',
            ]);
        });
    }
};
