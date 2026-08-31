<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mirrors `blog_seos`' final column set (create_blog_seos_table +
     * add_missing_fields_to_blog_seos_table + make_blog_seo_defaults_nullable)
     * exactly, swapped to a `news_id` FK. `schema_type` defaults to
     * 'NewsArticle' instead of 'Article' — the correct schema.org type for a
     * company newsroom/press-release item, distinct from BlogPosting.
     *
     * Nullable-with-default columns are declared nullable from the start
     * here (unlike blog_seos, which needed a follow-up migration to fix
     * this) since a column default only applies when the column is omitted
     * entirely, not when NULL is passed explicitly — and the SEO form/
     * autosave flow does send explicit NULLs for unfilled fields.
     */
    public function up(): void
    {
        Schema::create('news_seos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('news_id')->constrained('news')->cascadeOnDelete();

            // Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            // Twitter
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();

            // Technical SEO
            $table->string('canonical_url')->nullable();
            $table->string('robots')->nullable(); // index, noindex
            $table->string('schema_type')->nullable()->default('NewsArticle');

            $table->text('meta_keywords')->nullable();
            $table->string('schema_author')->nullable();
            $table->decimal('schema_rating_value', 3, 2)->nullable();
            $table->unsignedInteger('schema_rating_count')->nullable();
            $table->string('twitter_card')->nullable()->default('summary_large_image');
            $table->string('twitter_creator')->nullable();
            $table->string('hreflang')->nullable()->default('en');
            $table->decimal('sitemap_priority', 2, 1)->nullable()->default(0.8);
            $table->string('sitemap_changefreq')->nullable()->default('daily');
            $table->text('custom_head_scripts')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_seos');
    }
};
