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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Payer
            $table->enum('type', ['consultation_payment', 'refund', 'payout'])->default('consultation_payment');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('lawyer_payout', 10, 2)->default(0);
            $table->enum('status', ['pending', 'held', 'captured', 'refunded', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); // card, gcash, grabpay, paymaya
            $table->string('paymongo_payment_id')->nullable();
            $table->string('paymongo_payment_intent_id')->nullable();
            $table->text('payment_details')->nullable(); // JSON with additional payment info
            $table->text('failure_reason')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['consultation_id', 'status']);
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
