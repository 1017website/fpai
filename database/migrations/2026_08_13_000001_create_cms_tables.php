<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('superadmin')->after('password');
            $table->boolean('must_change_password')->default(false)->after('role');
        });

        Schema::create('brochure_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('page_number')->unique();
            $table->unsignedTinyInteger('position')->index();
            $table->string('slug')->unique();
            $table->string('label');
            $table->string('image_path');
            $table->string('theme', 20)->default('dark');
            $table->string('alt_text');
            $table->boolean('show_in_navigation')->default(false);
            $table->string('navigation_label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->longText('value')->nullable();
            $table->string('group', 30)->index();
            $table->string('type', 30)->default('text');
            $table->string('label');
            $table->text('help_text')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('visitor_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 20)->default('pageview')->index();
            $table->string('session_id')->nullable()->index();
            $table->char('ip_hash', 64)->nullable()->index();
            $table->string('path')->default('/');
            $table->string('section_slug')->nullable()->index();
            $table->text('referrer')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_events');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('brochure_pages');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'must_change_password']);
        });
    }
};
