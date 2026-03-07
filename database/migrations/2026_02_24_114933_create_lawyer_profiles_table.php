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
        Schema::create('lawyer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ibp_number')->unique();
            $table->text('bio')->nullable();
            $table->integer('years_experience')->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->string('law_school')->nullable();
            $table->year('graduation_year')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_consultations')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_available')->default(true);
            $table->string('username')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_profiles');
    }
};
