<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scheduled_calls', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 254);
            $table->string('phone', 20)->nullable();
            $table->date('preferred_date');
            $table->string('timezone', 10);
            $table->string('time_slot', 10);
            $table->string('call_topic', 100);
            $table->boolean('wants_video')->default(false);
            $table->string('video_platform', 50)->nullable();
            $table->text('notes')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_calls');
    }
};
