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
            $table->string('country')->nullable()->after('birthday');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->decimal('latitude', 10, 8)->nullable()->after('city');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->tinyInteger('style_life')->nullable()->after('longitude');
            $table->float('height', 3, 2)->nullable()->after('style_life');
            $table->tinyInteger('physical_type')->nullable()->after('height');
            $table->tinyInteger('skin_tone')->nullable()->after('physical_type');
            $table->tinyInteger('eye_color')->nullable()->after('skin_tone');
            $table->tinyInteger('drink')->nullable()->after('eye_color');
            $table->tinyInteger('smoke')->nullable()->after('drink');
            $table->tinyInteger('hair_type')->nullable()->after('smoke');
            $table->tinyInteger('hair_color')->nullable()->after('hair_type');
            $table->tinyInteger('marital_status')->nullable()->after('hair_color');
            $table->tinyInteger('beard_size')->nullable()->after('marital_status');
            $table->tinyInteger('beard_color')->nullable()->after('beard_size');
            $table->tinyInteger('children')->nullable()->after('beard_color');
            $table->tinyInteger('tattoo')->nullable()->after('children');
            $table->tinyInteger('academic_background')->nullable()->after('tattoo');
            $table->string('occupation')->nullable()->after('academic_background');
            $table->tinyInteger('monthly_income')->nullable()->after('occupation');
            $table->tinyInteger('personal_patrimony')->nullable()->after('monthly_income');
            $table->text('hobbies')->nullable()->after('personal_patrimony');
            $table->string('first_impression')->nullable()->after('hobbies');
            $table->text('about')->nullable()->after('first_impression');

            $table->index('country');
            $table->index('state');
            $table->index('city');
            $table->index('style_life');
            $table->index('physical_type');
            $table->index('skin_tone');
            $table->index('eye_color');
            $table->index('drink');
            $table->index('smoke');
            $table->index('hair_type');
            $table->index('hair_color');
            $table->index('marital_status');
            $table->index('beard_size');
            $table->index('beard_color');
            $table->index('children');
            $table->index('tattoo');
            $table->index('academic_background');
            $table->index('monthly_income');
            $table->index('personal_patrimony');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->dropIndex(['country']);
            $table->dropIndex(['state']);
            $table->dropIndex(['city']);
            $table->dropIndex(['style_life']);
            $table->dropIndex(['physical_type']);
            $table->dropIndex(['skin_tone']);
            $table->dropIndex(['eye_color']);
            $table->dropIndex(['drink']);
            $table->dropIndex(['smoke']);
            $table->dropIndex(['hair_type']);
            $table->dropIndex(['hair_color']);
            $table->dropIndex(['marital_status']);
            $table->dropIndex(['beard_size']);
            $table->dropIndex(['beard_color']);
            $table->dropIndex(['children']);
            $table->dropIndex(['tattoo']);
            $table->dropIndex(['academic_background']);
            $table->dropIndex(['monthly_income']);
            $table->dropIndex(['personal_patrimony']);

            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('style_life');
            $table->dropColumn('height');
            $table->dropColumn('physical_type');
            $table->dropColumn('skin_tone');
            $table->dropColumn('eye_color');
            $table->dropColumn('drink');
            $table->dropColumn('smoke');
            $table->dropColumn('hair_type');
            $table->dropColumn('hair_color');
            $table->dropColumn('marital_status');
            $table->dropColumn('beard_size');
            $table->dropColumn('beard_color');
            $table->dropColumn('children');
            $table->dropColumn('tattoo');
            $table->dropColumn('academic_background');
            $table->dropColumn('occupation');
            $table->dropColumn('monthly_income');
            $table->dropColumn('personal_patrimony');
            $table->dropColumn('hobbies');
            $table->dropColumn('first_impression');
            $table->dropColumn('about');
        });
    }
};
