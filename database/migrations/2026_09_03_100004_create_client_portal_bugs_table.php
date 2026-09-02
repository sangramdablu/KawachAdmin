<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portal_bugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portal_project_id')
                  ->constrained('client_portal_projects')
                  ->cascadeOnDelete();
            $table->foreignId('client_user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('steps_to_reproduce')->nullable();
            $table->text('expected_result')->nullable();
            $table->text('actual_result')->nullable();
            $table->string('attachment_path')->nullable(); // image or short video
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['reported', 'under_review', 'in_progress', 'fixed', 'ready_for_testing', 'closed'])
                  ->default('reported');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_bugs');
    }
};
