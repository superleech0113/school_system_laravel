<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMonthlyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_payments', function (Blueprint $table) {
            \DB::statement('ALTER TABLE `monthly_payments` CHANGE `price` `price` DOUBLE NOT NULL');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->double('discount_amount')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
        });

        Schema::table('monthly_payments', function(Blueprint $table) {
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('restrict');
            $table->foreign('subscription_id')->references('id')->on('stripe_subscriptions')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_payments', function(Blueprint $table) {
            $table->dropForeign('monthly_payments_discount_id_foreign');
            $table->dropIndex('monthly_payments_discount_id_foreign');
            $table->dropForeign('monthly_payments_subscription_id_foreign');
            $table->dropIndex('monthly_payments_subscription_id_foreign');
        });

        Schema::table('monthly_payments', function (Blueprint $table) {
            \DB::statement('ALTER TABLE `monthly_payments` CHANGE `price` `price` INT NOT NULL');
            $table->dropColumn('discount_id');
            $table->dropColumn('discount_amount');
            $table->dropColumn('subscription_id');
        });
    }
}
