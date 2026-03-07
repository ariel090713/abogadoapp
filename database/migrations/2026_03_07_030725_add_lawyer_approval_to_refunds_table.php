<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->unsignedBigInteger('lawyer_id')->nullable()->after('user_id');
            $table->enum('lawyer_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('lawyer_notes')->nullable()->after('lawyer_approval_status');
            $table->timestamp('lawyer_responded_at')->nullable()->after('lawyer_notes');
            
            $table->foreign('lawyer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropForeign(['lawyer_id']);
            $table->dropColumn(['lawyer_id', 'lawyer_approval_status', 'lawyer_notes', 'lawyer_responded_at']);
        });
    }
};
