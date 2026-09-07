<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department');
            $table->string('location');
            $table->string('type'); // Full-time, Part-time, Contract, Internship, Freelance
            $table->string('experience_level');
            $table->unsignedSmallInteger('openings')->default(1);
            $table->string('salary_range')->nullable();
            $table->date('application_deadline')->nullable();
            $table->text('summary');
            $table->json('responsibilities')->nullable();
            $table->json('requirements')->nullable();
            $table->json('nice_to_have')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
