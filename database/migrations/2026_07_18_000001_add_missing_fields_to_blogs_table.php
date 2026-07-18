<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('featured_image');
            $table->string('image_title')->nullable()->after('image_alt');
            $table->string('image_caption')->nullable()->after('image_title');
            $table->string('visibility')->default('public')->after('status');
            $table->string('post_password')->nullable()->after('visibility');
            $table->boolean('allow_comments')->default(true)->after('post_password');
        });

        // Schema Builder can't alter enum value lists portably, so this is raw SQL.
        DB::statement("ALTER TABLE blogs MODIFY status ENUM('draft','published','scheduled','pending') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE blogs MODIFY status ENUM('draft','published','scheduled') NOT NULL DEFAULT 'draft'");

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'image_alt',
                'image_title',
                'image_caption',
                'visibility',
                'post_password',
                'allow_comments',
            ]);
        });
    }
};
