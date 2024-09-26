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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('gender');
            $table->tinyInteger('interested');
            $table->tinyInteger('profile');
            $table->date('birthday');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('gender');
            $table->index('interested');
            $table->index('profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['interested']);
            $table->dropIndex(['profile']);
        });
        Schema::dropIfExists('user_profiles');
    }
};
