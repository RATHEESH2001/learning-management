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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            // Link to module (must exist) — unsignedBigInteger by default to match $table->id()
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->nullable()->index();

            // Markdown content
            $table->text('content_markdown')->nullable();

            // Video: either uploaded path (handled by medialibrary or stored path) or external url
            $table->string('video_path')->nullable()->comment('Storage path if uploaded');
            $table->string('video_url')->nullable()->comment('External URL (YouTube/Vimeo)');

            // Metadata
            $table->integer('duration_seconds')->nullable(); // optional, in seconds
            $table->integer('position')->default(0)->index(); // ordering within module
            $table->boolean('is_free')->default(false);

            // Visibility / publication
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
        Schema::dropIfExists('lessons');
    }
};
