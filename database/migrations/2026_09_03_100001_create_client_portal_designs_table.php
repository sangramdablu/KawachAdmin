<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portal_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portal_project_id')
                  ->constrained('client_portal_projects')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->string('version')->default('v1');
            $table->string('image_path');
            $table->string('figma_url')->nullable();
            $table->enum('status', ['pending', 'approved', 'changes_requested'])->default('pending');
            $table->foreignId('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_designs');
    }
};
