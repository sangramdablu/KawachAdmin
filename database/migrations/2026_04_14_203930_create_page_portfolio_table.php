<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_portfolio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->text('portfolio_desc')->nullable();
            $table->string('portfolio_category', 50)->nullable();
            $table->unsignedSmallInteger('portfolio_year')->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->text('portfolio_tech')->nullable();
            $table->longText('portfolio_content')->nullable();
            $table->json('gallery')->nullable();
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_portfolio'); }
};
