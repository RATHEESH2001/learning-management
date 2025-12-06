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
        Schema::create('modules', function (Blueprint $table) {
    $table->id();

    // Each module belongs to a course
    $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');

    // Module info
    $table->string('title');
    $table->string('slug')->nullable()->unique();
    $table->text('summary')->nullable();
    $table->integer('position')->default(0); // order within the course

    // Optional progress & visibility
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
