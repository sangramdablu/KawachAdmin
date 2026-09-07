<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Target countries (ISO 3166-1 alpha-2, lowercase — e.g. "us","in","gb").
            // Empty/null = Global: no country bias, shown normally to everyone.
            $table->json('countries')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('countries');
        });
    }
};
