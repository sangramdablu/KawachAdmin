<?php
// ============================================================
//  FILE: database/migrations/2024_01_01_000001_create_pages_table.php
//  Master pages table — shared by ALL page types
// ============================================================
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->string('page_type', 30);
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->enum('status', ['draft', 'pending', 'published', 'scheduled'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'password'])->default('public');
            $table->string('page_password', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('page_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
 
            // ── Featured Image ─────────────────────
            $table->string('featured_image', 500)->nullable();
            $table->string('image_alt', 200)->nullable();
            $table->string('image_title', 200)->nullable();
 
            // ── SEO / Meta ─────────────────────────
            $table->string('focus_keyword', 200)->nullable();
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('robots', 50)->default('index, follow');
            $table->string('schema_type', 50)->default('WebPage');
 
            // ── Open Graph / Social ────────────────
            $table->string('og_title', 200)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('twitter_card', 50)->default('summary_large_image');
 
            // ── Advanced ───────────────────────────
            $table->string('hreflang', 10)->default('en');
            $table->decimal('sitemap_priority', 2, 1)->default(0.9);
            $table->string('sitemap_changefreq', 20)->default('weekly');
            $table->text('custom_head_script')->nullable();
 
            // ── Tags (comma-separated) ─────────────
            $table->text('tags')->nullable();
 
            $table->timestamps();
            $table->softDeletes();
 
            // ── Indexes ────────────────────────────
            $table->index('page_type');
            $table->index('status');
            $table->index('is_featured');
            $table->index('sort_order');
            $table->index('published_at');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};