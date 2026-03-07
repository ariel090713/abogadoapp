<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->string('document_type', 100);
            $table->text('description');
            $table->timestamp('deadline')->nullable();
            $table->decimal('review_fee', 10, 2)->nullable();
            $table->string('status')->default('pending'); // 'pending', 'submitted', 'reviewed', 'declined'
            $table->timestamp('submitted_at')->nullable();
            $table->json('document_paths')->nullable(); // Store multiple document paths
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            $table->index('consultation_id');
            $table->index('status');
            $table->index('requested_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
