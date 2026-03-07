<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('lawyer_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reference_number')->nullable()->after('paymongo_payment_intent_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['lawyer_id']);
            $table->dropColumn(['lawyer_id', 'reference_number']);
        });
    }
};
