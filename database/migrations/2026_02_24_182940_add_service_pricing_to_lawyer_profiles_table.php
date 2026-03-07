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
        Schema::table('lawyer_profiles', function (Blueprint $table) {
            // Remove old single rate field
            $table->dropColumn('rate_per_15min');
            
            // Chat Consultation Rates (per duration)
            $table->decimal('chat_rate_15min', 8, 2)->nullable()->comment('Chat consultation rate for 15 minutes');
            $table->decimal('chat_rate_30min', 8, 2)->nullable()->comment('Chat consultation rate for 30 minutes');
            $table->decimal('chat_rate_60min', 8, 2)->nullable()->comment('Chat consultation rate for 60 minutes');
            
            // Video Consultation Rates (per duration)
            $table->decimal('video_rate_15min', 8, 2)->nullable()->comment('Video consultation rate for 15 minutes');
            $table->decimal('video_rate_30min', 8, 2)->nullable()->comment('Video consultation rate for 30 minutes');
            $table->decimal('video_rate_60min', 8, 2)->nullable()->comment('Video consultation rate for 60 minutes');
            
            // Document Review (minimum price)
            $table->decimal('document_review_min_price', 8, 2)->nullable()->comment('Document review minimum price');
            
            // Service availability flags
            $table->boolean('offers_chat_consultation')->default(false);
            $table->boolean('offers_video_consultation')->default(false);
            $table->boolean('offers_document_review')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lawyer_profiles', function (Blueprint $table) {
            // Add back old field
            $table->decimal('rate_per_15min', 8, 2)->nullable();
            
            // Remove new fields
            $table->dropColumn([
                'chat_rate_15min',
                'chat_rate_30min',
                'chat_rate_60min',
                'video_rate_15min',
                'video_rate_30min',
                'video_rate_60min',
                'document_review_min_price',
                'offers_chat_consultation',
                'offers_video_consultation',
                'offers_document_review',
            ]);
        });
    }
};