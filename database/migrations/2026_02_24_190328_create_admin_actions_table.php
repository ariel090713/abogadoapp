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
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action_type'); // verify_lawyer, suspend_user, process_payout, resolve_dispute
            $table->string('target_type'); // User, LawyerProfile, Consultation, Payout
            $table->unsignedBigInteger('target_id');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Additional action data
            $table->timestamps();
            
            $table->index(['admin_id', 'created_at']);
            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_actions');
    }
};
