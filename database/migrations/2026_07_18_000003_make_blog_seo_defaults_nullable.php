<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The previous migration gave these columns a DB default but not
     * nullable, so any write that explicitly sends NULL (e.g. autosave,
     * which doesn't collect every SEO field) violates the NOT NULL
     * constraint instead of falling back to the default. A column default
     * only applies when the column is omitted entirely, not when NULL is
     * passed explicitly. Using raw SQL since doctrine/dbal (required by
     * Schema::table()->change()) isn't installed in this project.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE blog_seos MODIFY twitter_card VARCHAR(255) NULL DEFAULT 'summary_large_image'");
        DB::statement("ALTER TABLE blog_seos MODIFY hreflang VARCHAR(255) NULL DEFAULT 'en'");
        DB::statement("ALTER TABLE blog_seos MODIFY sitemap_priority DECIMAL(2,1) NULL DEFAULT 0.9");
        DB::statement("ALTER TABLE blog_seos MODIFY sitemap_changefreq VARCHAR(255) NULL DEFAULT 'daily'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE blog_seos MODIFY twitter_card VARCHAR(255) NOT NULL DEFAULT 'summary_large_image'");
        DB::statement("ALTER TABLE blog_seos MODIFY hreflang VARCHAR(255) NOT NULL DEFAULT 'en'");
        DB::statement("ALTER TABLE blog_seos MODIFY sitemap_priority DECIMAL(2,1) NOT NULL DEFAULT 0.9");
        DB::statement("ALTER TABLE blog_seos MODIFY sitemap_changefreq VARCHAR(255) NOT NULL DEFAULT 'daily'");
    }
};
