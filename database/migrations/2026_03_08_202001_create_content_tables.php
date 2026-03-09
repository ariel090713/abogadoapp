<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Legal Guides
        Schema::create('legal_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('category'); // family_law, criminal_law, labor_law, etc.
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // News
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Blogs
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('category'); // insights, opinions, tips, etc.
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('event_type'); // webinar, seminar, workshop
            $table->dateTime('event_date');
            $table->string('location')->nullable(); // for physical events
            $table->string('meeting_link')->nullable(); // for online events
            $table->integer('max_participants')->nullable();
            $table->integer('registered_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        // Galleries
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // photo, video
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        // Gallery Items
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('file_path'); // image or video path
            $table->string('thumbnail_path')->nullable(); // for videos
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Downloadables
        Schema::create('downloadables', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('file_path');
            $table->string('file_type'); // pdf, docx, xlsx
            $table->integer('file_size'); // in bytes
            $table->string('category'); // contracts, forms, templates, guides
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('downloads')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('downloadables');
        Schema::dropIfExists('events');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('news');
        Schema::dropIfExists('legal_guides');
    }
};
