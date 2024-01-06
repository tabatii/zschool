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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->enum('gender', ['male', 'female']);
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('mobile')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->text('address')->nullable();
            $table->json('attachments')->nullable();
            $table->json('details')->nullable();
            $table->string('panel_color')->nullable();
            $table->boolean('top_navigation')->nullable();
            $table->boolean('wide_content')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
