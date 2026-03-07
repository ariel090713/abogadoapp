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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained('users')->onDelete('cascade');
            $table->string('consultation_type'); // chat, video, document_review
            $table->integer('duration')->nullable(); // in minutes (for chat/video)
            $table->decimal('rate', 10, 2); // consultation rate
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2); // rate + platform_fee
            $table->enum('status', ['pending', 'accepted', 'declined', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->text('client_notes')->nullable(); // Client's initial message/concern
            $table->text('lawyer_notes')->nullable(); // Lawyer's private notes
            $table->text('decline_reason')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->string('video_room_sid')->nullable(); // Twilio room SID
            $table->boolean('recording_enabled')->default(false);
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index(['lawyer_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
