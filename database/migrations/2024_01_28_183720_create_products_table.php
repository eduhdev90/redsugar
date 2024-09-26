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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('external_id')->nullable();
            $table->tinyInteger('profile');
            $table->tinyInteger('type_plan');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();

            $table->index('external_id');
            $table->index('profile');
            $table->index('type_plan');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['external_id']);
            $table->dropIndex(['profile']);
            $table->dropIndex(['type_plan']);
            $table->dropIndex(['status']);
        });
        Schema::dropIfExists('products');
    }
};
