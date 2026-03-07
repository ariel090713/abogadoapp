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
        // Update the status enum to include 'revision_requested'
        DB::statement("ALTER TABLE `document_drafting_requests` MODIFY COLUMN `status` ENUM('pending_payment', 'paid', 'in_progress', 'completed', 'cancelled', 'expired', 'revision_requested') NOT NULL DEFAULT 'pending_payment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'revision_requested' from the enum
        DB::statement("ALTER TABLE `document_drafting_requests` MODIFY COLUMN `status` ENUM('pending_payment', 'paid', 'in_progress', 'completed', 'cancelled', 'expired') NOT NULL DEFAULT 'pending_payment'");
    }
};
