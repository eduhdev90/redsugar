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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->char('currency', 3);
            $table->integer('unit_amount', unsigned: true);
            $table->tinyInteger('period');
            $table->string('external_id')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('product_id')->references('id')->on('products');
            $table->index('period');
            $table->index('external_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex(['period']);
            $table->dropIndex(['external_id']);
            $table->dropIndex(['status']);
        });
        Schema::dropIfExists('prices');
    }
};
