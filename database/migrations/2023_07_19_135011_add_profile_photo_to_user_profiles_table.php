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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_photo_id')->nullable()->after('about');
            $table->string('profile_image')->nullable()->after('profile_photo_id');

            $table->foreign('profile_photo_id')->references('id')->on('user_photos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['profile_photo_id']);
            $table->dropColumn('profile_photo_id');
            $table->dropColumn('profile_image');
        });
    }
};
