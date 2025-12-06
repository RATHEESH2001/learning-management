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
        Schema::create('courses', function (Blueprint $table) {
    $table->id();

    // Basic course info
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('short_description')->nullable();
    $table->longText('description')->nullable();

    // Author / instructor reference (if users table exists)
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

    // Optional metadata
    $table->string('level')->default('beginner'); // beginner, intermediate, advanced
    $table->integer('duration')->nullable(); // in minutes
    $table->decimal('price', 8, 2)->default(0); // 0 = free
    $table->boolean('is_free')->default(false);
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();

    // Media / thumbnails
    $table->string('thumbnail')->nullable(); // store image path
    $table->string('video_intro')->nullable(); // optional intro video

    // Ratings & statistics (optional, can extend later)
    $table->integer('views')->default(0);
    $table->integer('enrollments_count')->default(0);
    $table->decimal('rating', 3, 2)->default(0); // 0–5 scale

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
