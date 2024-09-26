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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable();
            $table->unsignedBigInteger('user_profile_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('price_id')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->char('currency', 3)->nullable();
            $table->unsignedInteger('unit_amount');
            $table->tinyInteger('payment_method')->nullable();
            $table->timestamps();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('price_id')->references('id')->on('prices');
            $table->index('external_id');
            $table->index('status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['user_profile_id']);
            $table->dropForeign(['price_id']);
            $table->dropIndex(['external_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_method']);
        });
        Schema::dropIfExists('subscriptions');
    }
};
