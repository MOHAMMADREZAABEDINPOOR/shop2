<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->string('status')->default('active')->index()->after('avatar');
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('national_code', 10)->nullable()->unique();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->default('منزل'); // e.g. Home / Work
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('province');
            $table->string('city');
            $table->string('postal_code', 20);
            $table->text('address_line');
            $table->string('unit')->nullable();
            $table->string('plaque')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('user_profiles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'status']);
        });
    }
};
