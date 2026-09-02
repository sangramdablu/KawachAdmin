<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portal_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portal_project_id')
                  ->constrained('client_portal_projects')
                  ->cascadeOnDelete();
            $table->foreignId('client_user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('screenshot_path')->nullable();
            $table->text('expected_result')->nullable();
            $table->decimal('estimated_hours', 6, 2)->nullable();
            $table->decimal('additional_cost', 10, 2)->nullable();
            $table->string('deadline_impact')->nullable(); // free text e.g. "+3 days"
            $table->foreignId('responded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->text('response_note')->nullable();
            $table->text('client_note')->nullable(); // clarification question / rejection reason
            $table->enum('status', ['submitted', 'responded', 'approved', 'rejected', 'clarification_requested'])
                  ->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_change_requests');
    }
};
