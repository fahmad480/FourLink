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
        Schema::table('link_groups', function (Blueprint $table) {
            $table->string('password')->nullable()->after('views_count');
            $table->string('instagram_url')->nullable()->after('password');
            $table->string('facebook_url')->nullable()->after('instagram_url');
            $table->string('x_url')->nullable()->after('facebook_url');
            $table->string('threads_url')->nullable()->after('x_url');
            $table->string('website_url')->nullable()->after('threads_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_groups', function (Blueprint $table) {
            $table->dropColumn(['password', 'instagram_url', 'facebook_url', 'x_url', 'threads_url', 'website_url']);
        });
    }
};
