<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_drafting_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lawyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lawyer_document_service_id')->constrained('lawyer_document_services')->cascadeOnDelete();
            $table->string('document_name');
            $table->json('form_data'); // Client's filled form data
            $table->decimal('price', 10, 2);
            $table->enum('status', [
                'pending_payment',
                'paid', 
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending_payment');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_intent_id')->nullable();
            $table->timestamp('payment_deadline')->nullable();
            $table->timestamp('completion_deadline')->nullable();
            $table->string('draft_document_path')->nullable(); // Lawyer's completed document
            $table->text('client_notes')->nullable();
            $table->text('lawyer_notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('client_id');
            $table->index('lawyer_id');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_drafting_requests');
    }
};
