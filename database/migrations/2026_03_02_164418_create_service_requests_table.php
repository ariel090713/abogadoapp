<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->string('request_type'); // 'follow_up', 'additional_service'
            $table->string('service_type'); // 'chat', 'video', 'document_review'
            $table->text('description');
            $table->timestamp('proposed_date')->nullable();
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->string('status')->default('pending'); // 'pending', 'accepted', 'declined', 'completed'
            $table->text('response_notes')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('consultation_id');
            $table->index('status');
            $table->index('requested_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
