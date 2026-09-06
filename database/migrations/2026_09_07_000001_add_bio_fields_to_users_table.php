<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends the Team-page display fields (see
 * 2026_09_02_000001_add_team_fields_to_users_table.php) with a short bio,
 * a public LinkedIn profile URL, and years of experience — requested so the
 * public site's team cards can show more than just name/designation.
 * Purely additive and nullable; no existing row's meaning changes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('responsibilities');
            $table->string('linkedin_url')->nullable()->after('bio');
            $table->unsignedTinyInteger('years_experience')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'linkedin_url', 'years_experience']);
        });
    }
};
