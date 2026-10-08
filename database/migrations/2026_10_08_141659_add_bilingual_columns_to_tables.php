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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_fa')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_fa')->nullable()->after('description_en');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_fa')->nullable()->after('name_en');
            $table->text('short_description_en')->nullable()->after('short_description');
            $table->text('short_description_fa')->nullable()->after('short_description_en');
            $table->longText('description_en')->nullable()->after('description');
            $table->longText('description_fa')->nullable()->after('description_en');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_fa')->nullable()->after('title_en');
            $table->text('subtitle_en')->nullable()->after('subtitle');
            $table->text('subtitle_fa')->nullable()->after('subtitle_en');
            $table->string('badge_text_en')->nullable()->after('badge_text');
            $table->string('badge_text_fa')->nullable()->after('badge_text_en');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_fa')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_fa')->nullable()->after('description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_fa', 'description_en', 'description_fa']);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_fa', 'subtitle_en', 'subtitle_fa', 'badge_text_en', 'badge_text_fa']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_fa', 'short_description_en', 'short_description_fa', 'description_en', 'description_fa']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_fa', 'description_en', 'description_fa']);
        });
    }
};
