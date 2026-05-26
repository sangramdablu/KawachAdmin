<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->string('faq_category', 50)->default('general');
            $table->unsignedInteger('faq_order')->default(0);
            $table->json('faq_items')->nullable();   // [{question, answer}]
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_faqs'); }
};
