<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mirrors the `blogs` table structure (see
     * 2026_03_30_205838_create_blogs_table.php and
     * 2026_07_18_000001_add_missing_fields_to_blogs_table.php) but for the
     * company Newsroom module — press releases / company announcements,
     * distinct from editorial Blog content. Reuses the shared `categories`
     * and `tags` tables (no `type` discriminator) rather than creating
     * news-specific taxonomy tables.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Basic Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // Media
            $table->string('featured_image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('image_title')->nullable();
            $table->string('image_caption')->nullable();

            // Relations
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();

            // "As featured in {Publication}" style items. Null means this is
            // the company's own announcement rather than external coverage.
            $table->string('external_source_name')->nullable();
            $table->string('external_source_url')->nullable();

            // SEO (Core)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('focus_keyword')->nullable();

            // Status
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->string('visibility')->default('public');
            $table->timestamp('published_at')->nullable();

            // Stats
            $table->integer('views')->default(0);
            $table->integer('reading_time')->nullable(); // minutes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
