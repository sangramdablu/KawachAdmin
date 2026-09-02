<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portal_design_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portal_design_id')
                  ->constrained('client_portal_designs')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->text('body');
            $table->decimal('x_position', 5, 2)->nullable(); // % across image width
            $table->decimal('y_position', 5, 2)->nullable(); // % across image height
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_design_comments');
    }
};
