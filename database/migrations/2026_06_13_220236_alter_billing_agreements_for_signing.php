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
        Schema::table('billing_agreements', function (Blueprint $table) {
            $table->string('signing_token')->nullable()->unique();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->ipAddress('signed_ip')->nullable();
            $table->text('signed_user_agent')->nullable();
            $table->boolean('portal_created')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_agreements');
    }
};
