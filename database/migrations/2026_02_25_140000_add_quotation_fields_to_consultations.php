<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('consultations', 'quoted_price')) {
                $table->decimal('quoted_price', 10, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('consultations', 'quote_notes')) {
                $table->text('quote_notes')->nullable()->after('quoted_price');
            }
            if (!Schema::hasColumn('consultations', 'quote_provided_at')) {
                $table->timestamp('quote_provided_at')->nullable()->after('quote_notes');
            }
            if (!Schema::hasColumn('consultations', 'quote_accepted_at')) {
                $table->timestamp('quote_accepted_at')->nullable()->after('quote_provided_at');
            }
        });
        
        // Update status enum to include 'awaiting_quote_approval'
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('pending', 'awaiting_quote_approval', 'accepted', 'declined', 'scheduled', 'payment_pending', 'in_progress', 'completed', 'cancelled', 'expired', 'payment_failed') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['quoted_price', 'quote_notes', 'quote_provided_at', 'quote_accepted_at']);
        });
        
        // Revert status enum
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('pending', 'accepted', 'declined', 'scheduled', 'payment_pending', 'in_progress', 'completed', 'cancelled', 'expired', 'payment_failed') DEFAULT 'pending'");
    }
};
