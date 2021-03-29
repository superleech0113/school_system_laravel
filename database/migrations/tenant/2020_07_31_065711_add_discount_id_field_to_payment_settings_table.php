<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountIdFieldToPaymentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->nullable();
        });

        Schema::table('payment_settings', function(Blueprint $table) {
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
        Schema::table('payment_settings', function(Blueprint $table) {
            $table->dropForeign('payment_settings_discount_id_foreign');
            $table->dropIndex('payment_settings_discount_id_foreign');
        });

        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn('discount_id');
        });
    }
}
