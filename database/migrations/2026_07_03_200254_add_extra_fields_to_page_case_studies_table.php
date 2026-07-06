<?php
// database/migrations/2026_07_04_000000_add_extra_fields_to_page_case_studies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_case_studies', function (Blueprint $table) {
            $table->string('business_size')->nullable()->after('client_industry');
            $table->string('location')->nullable()->after('business_size');
            $table->string('business_model')->nullable()->after('location');

            $table->json('existing_challenges')->nullable()->after('challenge');
            $table->json('goals')->nullable()->after('solution');
            $table->json('solution_modules')->nullable()->after('goals');
            $table->json('tech_stack')->nullable()->after('technologies');
            $table->json('cs_process_steps')->nullable()->after('tech_stack');
            $table->json('achievements')->nullable()->after('cs_process_steps');
            $table->json('before_after')->nullable()->after('achievements');
            $table->json('compliance_items')->nullable()->after('before_after');
            $table->json('gallery')->nullable()->after('compliance_items');
        });
    }

    public function down(): void
    {
        Schema::table('page_case_studies', function (Blueprint $table) {
            $table->dropColumn([
                'business_size','location','business_model',
                'existing_challenges','goals','solution_modules','tech_stack',
                'cs_process_steps','achievements','before_after',
                'compliance_items','gallery',
            ]);
        });
    }
};