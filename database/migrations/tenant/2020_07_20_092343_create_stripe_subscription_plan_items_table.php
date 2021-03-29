<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeSubscriptionPlanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_subscription_plan_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stripe_subscription_id');
            $table->unsignedBigInteger('plan_id');
            $table->integer('quantity');
            $table->string('stripe_item_id');
        });

        Schema::table('stripe_subscription_plan_items', function(Blueprint $table) {
            $table->foreign('stripe_subscription_id')->references('id')->on('stripe_subscriptions')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_subscription_plan_items', function(Blueprint $table) {
            $table->dropForeign('stripe_subscription_plan_items_stripe_subscription_id_foreign');
            $table->dropIndex('stripe_subscription_plan_items_stripe_subscription_id_foreign');
            $table->dropForeign('stripe_subscription_plan_items_plan_id_foreign');
            $table->dropIndex('stripe_subscription_plan_items_plan_id_foreign');
        });

        Schema::dropIfExists('stripe_subscription_plan_items');
    }
}
