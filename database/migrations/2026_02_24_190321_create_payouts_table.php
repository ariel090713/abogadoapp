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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('paymongo_payout_id')->nullable();
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable();
            $table->dateTime('requested_at');
            $table->dateTime('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users'); // Admin who processed
            $table->timestamps();
            
            $table->index(['lawyer_id', 'status']);
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
