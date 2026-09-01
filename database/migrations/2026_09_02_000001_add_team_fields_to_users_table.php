<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the Team-page / content-authorship display fields to the existing
 * users table. Purely additive: every existing row defaults to
 * is_team_member = false, and the rest of the new columns are nullable, so
 * no pre-existing user's meaning changes.
 *
 * Note: `team_role` here is a purely descriptive label (e.g. "Content Team",
 * "Engineering") shown on the Team page — it is NOT related to Spatie's
 * `roles` system (still handled entirely by HasRoles / the roles/
 * model_has_roles tables), which continues to control permissions.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_team_member')->default(false)->after('last_login_at');
            $table->string('designation')->nullable()->after('is_team_member');
            $table->string('team_role')->nullable()->after('designation');
            $table->text('responsibilities')->nullable()->after('team_role');
            $table->string('avatar')->nullable()->after('responsibilities');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_team_member', 'designation', 'team_role', 'responsibilities', 'avatar']);
        });
    }
};
