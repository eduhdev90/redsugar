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
        Schema::create('user_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_profile_id');
            $table->string('path');
            $table->tinyInteger('visibility');
            $table->timestamps();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles');
            $table->index('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_photos', function (Blueprint $table) {
            $table->dropForeign(['user_profile_id']);
            $table->dropIndex(['visibility']);
        });
        Schema::dropIfExists('user_photos');
    }
};
