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
        Schema::create('blog_seos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();

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
            $table->string('schema_type')->nullable(); // article, blogposting

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_seos');
    }
};
