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
        Schema::table('blog_seos', function (Blueprint $table) {
            $table->text('meta_keywords')->nullable()->after('schema_type');
            $table->string('schema_author')->nullable()->after('meta_keywords');
            $table->decimal('schema_rating_value', 3, 2)->nullable()->after('schema_author');
            $table->unsignedInteger('schema_rating_count')->nullable()->after('schema_rating_value');
            $table->string('twitter_card')->default('summary_large_image')->after('schema_rating_count');
            $table->string('twitter_creator')->nullable()->after('twitter_card');
            $table->string('hreflang')->default('en')->after('twitter_creator');
            $table->decimal('sitemap_priority', 2, 1)->default(0.9)->after('hreflang');
            $table->string('sitemap_changefreq')->default('daily')->after('sitemap_priority');
            $table->text('custom_head_scripts')->nullable()->after('sitemap_changefreq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_seos', function (Blueprint $table) {
            $table->dropColumn([
                'meta_keywords',
                'schema_author',
                'schema_rating_value',
                'schema_rating_count',
                'twitter_card',
                'twitter_creator',
                'hreflang',
                'sitemap_priority',
                'sitemap_changefreq',
                'custom_head_scripts',
            ]);
        });
    }
};
