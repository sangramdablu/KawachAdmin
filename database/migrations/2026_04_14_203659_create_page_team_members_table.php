<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
 
            $table->string('job_title', 150)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('member_email', 200)->nullable();
            $table->string('member_phone', 50)->nullable();
            $table->string('member_location', 150)->nullable();
 
            $table->longText('bio')->nullable();
            $table->json('skills')->nullable();
 
            $table->string('social_linkedin', 500)->nullable();
            $table->string('social_twitter', 500)->nullable();
            $table->string('social_github', 500)->nullable();
            $table->string('social_website', 500)->nullable();
 
            $table->timestamps();
        });
    }
 
    public function down(): void { Schema::dropIfExists('page_team_members'); }
};
