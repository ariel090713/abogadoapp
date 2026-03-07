<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('document_request_id')->nullable()->constrained('document_drafting_requests')->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Client who receives refund
            
            $table->enum('refund_type', ['full', 'partial'])->default('full');
            $table->decimal('refund_amount', 10, 2);
            $table->decimal('original_amount', 10, 2);
            
            $table->enum('reason', [
                'expired_lawyer_response',
                'expired_quote',
                'expired_payment',
                'lawyer_declined',
                'lawyer_cancelled',
                'client_cancelled',
                'document_not_delivered',
                'dispute',
                'other'
            ]);
            $table->text('detailed_reason')->nullable();
            
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'processing',
                'completed',
                'failed'
            ])->default('pending');
            
            $table->text('admin_notes')->nullable();
            $table->string('paymongo_refund_id')->nullable();
            
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('reason');
            $table->index(['transaction_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
