<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawyer_document_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('document_templates')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('form_fields'); // Customized or original form fields
            $table->decimal('price', 10, 2);
            $table->integer('estimated_client_time')->default(15); // Minutes to fill form
            $table->integer('estimated_completion_days')->default(3); // Business days to complete
            $table->boolean('is_active')->default(true);
            $table->integer('total_orders')->default(0);
            $table->timestamps();
            
            $table->index('lawyer_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyer_document_services');
    }
};
