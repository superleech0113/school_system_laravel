<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('stripe_subscription_id');
            $table->string('status');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->timestamps();
        });

        Schema::table('stripe_subscriptions', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_subscriptions', function(Blueprint $table) {
            $table->dropForeign('stripe_subscriptions_user_id_foreign');
            $table->dropIndex('stripe_subscriptions_user_id_foreign');
            $table->dropForeign('stripe_subscriptions_discount_id_foreign');
            $table->dropIndex('stripe_subscriptions_discount_id_foreign');
        });

        Schema::dropIfExists('stripe_subscriptions');
    }
}
