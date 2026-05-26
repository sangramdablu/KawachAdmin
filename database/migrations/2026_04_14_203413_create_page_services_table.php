<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->json('features')->nullable();
            $table->json('process_steps')->nullable();
            $table->string('price_from', 50)->nullable();
            $table->string('billing_cycle', 30)->nullable();
            $table->string('cta_url', 500)->nullable();
            $table->string('cta_text', 100)->nullable();
            $table->text('technologies')->nullable();
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_services'); }
};
