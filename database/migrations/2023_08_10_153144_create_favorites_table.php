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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_profile_id');
            $table->unsignedBigInteger('favorited_id');
            $table->timestamps();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles');
            $table->foreign('favorited_id')->references('id')->on('user_profiles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['user_profile_id']);
            $table->dropForeign(['favorited_id']);
        });
        Schema::dropIfExists('favorites');
    }
};
