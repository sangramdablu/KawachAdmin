<?php
// database/migrations/xxxx_xx_xx_add_features_faqs_to_page_case_studies.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_case_studies', function (Blueprint $table) {
            $table->json('cs_features')->nullable()->after('achievements');
            $table->json('cs_faqs')->nullable()->after('cs_features');
        });
    }

    public function down(): void
    {
        Schema::table('page_case_studies', function (Blueprint $table) {
            $table->dropColumn(['cs_features', 'cs_faqs']);
        });
    }
};