<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kawach_visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_uuid')->unique();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->unsignedInteger('visit_count')->default(1);
            $table->unsignedInteger('total_pageviews')->default(0);
            $table->unsignedInteger('total_time_seconds')->default(0);
            $table->string('entry_route')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('device_type')->nullable(); // desktop|mobile|tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->timestamps();

            $table->index('last_seen_at');
            $table->index('is_bot');
        });

        Schema::create('kawach_visitor_pageviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('kawach_visitors')->cascadeOnDelete();
            $table->string('route_name')->nullable();
            $table->string('path', 500);
            $table->string('referrer', 500)->nullable();
            $table->unsignedInteger('time_spent_seconds')->nullable();
            $table->timestamps();

            $table->index('path');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kawach_visitor_pageviews');
        Schema::dropIfExists('kawach_visitors');
    }
};
