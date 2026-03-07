<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('transactions', 'payment_intent_id')) {
                $table->dropColumn('payment_intent_id');
            }
            if (Schema::hasColumn('transactions', 'reference_number')) {
                $table->dropColumn('reference_number');
            }
            if (Schema::hasColumn('transactions', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('transactions', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            
            // Add new columns for PayMongo
            if (!Schema::hasColumn('transactions', 'platform_fee')) {
                $table->decimal('platform_fee', 10, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('transactions', 'lawyer_payout')) {
                $table->decimal('lawyer_payout', 10, 2)->default(0)->after('platform_fee');
            }
            if (!Schema::hasColumn('transactions', 'paymongo_payment_id')) {
                $table->string('paymongo_payment_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('transactions', 'paymongo_payment_intent_id')) {
                $table->string('paymongo_payment_intent_id')->nullable()->after('paymongo_payment_id');
            }
            if (!Schema::hasColumn('transactions', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('paymongo_payment_intent_id');
            }
            if (!Schema::hasColumn('transactions', 'failure_reason')) {
                $table->text('failure_reason')->nullable()->after('payment_details');
            }
            if (!Schema::hasColumn('transactions', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('failure_reason');
            }
            
            // Update status enum
            $table->enum('status', ['pending', 'held', 'captured', 'failed', 'refunded', 'cancelled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'platform_fee',
                'lawyer_payout',
                'paymongo_payment_id',
                'paymongo_payment_intent_id',
                'payment_details',
                'failure_reason',
                'processed_at',
            ]);
        });
    }
};
