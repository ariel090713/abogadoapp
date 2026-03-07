<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon_color')->default('blue'); // For UI color coding
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('sort_order');
        });

        // Seed initial categories
        DB::table('document_categories')->insert([
            ['slug' => 'contract', 'name' => 'Contracts', 'icon_color' => 'blue', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'affidavit', 'name' => 'Affidavits', 'icon_color' => 'red', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'agreement', 'name' => 'Agreements', 'icon_color' => 'blue', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'letter', 'name' => 'Legal Letters', 'icon_color' => 'green', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'deed', 'name' => 'Deeds', 'icon_color' => 'indigo', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'petition', 'name' => 'Petitions', 'icon_color' => 'red', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'complaint', 'name' => 'Complaints', 'icon_color' => 'red', 'sort_order' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'motion', 'name' => 'Motions', 'icon_color' => 'purple', 'sort_order' => 8, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'memorandum', 'name' => 'Memoranda', 'icon_color' => 'green', 'sort_order' => 9, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'waiver', 'name' => 'Waivers & Releases', 'icon_color' => 'purple', 'sort_order' => 10, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'power_of_attorney', 'name' => 'Power of Attorney', 'icon_color' => 'purple', 'sort_order' => 11, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'notarial', 'name' => 'Notarial Documents', 'icon_color' => 'indigo', 'sort_order' => 12, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'corporate', 'name' => 'Corporate Documents', 'icon_color' => 'blue', 'sort_order' => 13, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'employment', 'name' => 'Employment Documents', 'icon_color' => 'green', 'sort_order' => 14, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'real_estate', 'name' => 'Real Estate Documents', 'icon_color' => 'indigo', 'sort_order' => 15, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'other', 'name' => 'Other Documents', 'icon_color' => 'gray', 'sort_order' => 16, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};
