<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_profile_id')->constrained('lawyer_profiles')->onDelete('cascade');
            $table->date('blocked_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('is_full_day')->default(true);
            $table->timestamps();
            
            $table->index(['lawyer_profile_id', 'blocked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');
    }
};
