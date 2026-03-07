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
        // Add auto-accept toggle to lawyer_profiles
        Schema::table('lawyer_profiles', function (Blueprint $table) {
            $table->boolean('auto_accept_bookings')->default(false)->after('is_available');
        });

        // Add payment flow fields to consultations
        Schema::table('consultations', function (Blueprint $table) {
            $table->dateTime('accepted_at')->nullable()->after('scheduled_at');
            $table->dateTime('payment_deadline')->nullable()->after('accepted_at');
            $table->text('suggested_times')->nullable()->after('lawyer_notes'); // JSON array of alternative times
            $table->string('document_path')->nullable()->after('client_notes'); // For document review
            $table->decimal('quoted_price', 10, 2)->nullable()->after('total_amount'); // Lawyer's quote for document review
        });

        // Update status enum to include new statuses
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('pending', 'accepted', 'payment_pending', 'payment_failed', 'declined', 'scheduled', 'in_progress', 'completed', 'cancelled', 'expired') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lawyer_profiles', function (Blueprint $table) {
            $table->dropColumn('auto_accept_bookings');
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'accepted_at',
                'payment_deadline',
                'suggested_times',
                'document_path',
                'quoted_price',
            ]);
        });

        // Revert status enum
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('pending', 'accepted', 'declined', 'scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
