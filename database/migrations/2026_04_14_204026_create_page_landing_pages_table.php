<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->string('hero_headline', 250)->nullable();
            $table->string('hero_subheadline', 350)->nullable();
            $table->string('cta_primary_text', 100)->nullable();
            $table->string('cta_primary_url', 500)->nullable();
            $table->string('cta_secondary_text', 100)->nullable();
            $table->string('cta_secondary_url', 500)->nullable();
 
            $table->longText('landing_content')->nullable();
            $table->json('landing_stats')->nullable();
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_landing_pages'); }
};
