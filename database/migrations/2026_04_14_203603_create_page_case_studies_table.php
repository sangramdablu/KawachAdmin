<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_case_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->string('client_name', 150)->nullable();
            $table->string('client_industry', 100)->nullable();
            $table->string('project_duration', 80)->nullable();
            $table->string('completion_date', 20)->nullable();
            $table->string('project_url', 500)->nullable();
 
            $table->longText('challenge')->nullable();
            $table->longText('solution')->nullable();
 
            $table->json('kpis')->nullable();
            $table->text('technologies')->nullable();
 
            // Embedded testimonial from client
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_name', 150)->nullable();
            $table->string('testimonial_role', 150)->nullable();
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_case_studies'); }
};
