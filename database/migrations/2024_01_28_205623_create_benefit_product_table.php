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
        Schema::create('benefit_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('benefit_id');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('benefit_id')->references('id')->on('benefits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('benefit_product', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['benefit_id']);
        });
        Schema::dropIfExists('benefit_product');
    }
};
