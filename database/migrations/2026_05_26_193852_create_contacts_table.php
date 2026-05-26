<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('company')->nullable();
            $table->text('email');
            $table->text('phone')->nullable();
            $table->string('subject');
            $table->longText('services')->nullable();
            $table->string('budget')->nullable();
            $table->longText('message');
            $table->text('ip_address')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};