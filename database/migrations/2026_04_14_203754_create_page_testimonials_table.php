<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->text('testimonial_quote');
            $table->string('testimonial_name', 150);
            $table->string('testimonial_role', 200)->nullable();
            $table->string('testimonial_industry', 100)->nullable();
            $table->string('testimonial_service', 150)->nullable();
            $table->unsignedTinyInteger('testimonial_rating')->default(5);
            $table->string('testimonial_video', 500)->nullable();
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_testimonials'); }
};
