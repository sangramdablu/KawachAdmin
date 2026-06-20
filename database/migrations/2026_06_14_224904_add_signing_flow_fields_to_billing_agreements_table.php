<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billing_agreements', function (Blueprint $table) {
            // Signing flow fields — only add if not already present
            if (! Schema::hasColumn('billing_agreements', 'signing_token')) {
                $table->string('signing_token', 64)->nullable()->unique()->after('status');
            }
            if (! Schema::hasColumn('billing_agreements', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('signing_token');
            }
            if (! Schema::hasColumn('billing_agreements', 'viewed_at')) {
                $table->timestamp('viewed_at')->nullable()->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('billing_agreements', function (Blueprint $table) {
            $table->dropColumn(['signing_token', 'expires_at', 'viewed_at']);
        });
    }
};