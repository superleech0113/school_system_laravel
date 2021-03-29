<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPaymentBreakdownSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_breakdown_settings', function (Blueprint $table) {
            $table->renameColumn('amount', 'unit_amount');
            $table->integer('quantity')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_breakdown_settings', function (Blueprint $table) {
            $table->renameColumn('unit_amount', 'amount');
            $table->dropColumn('quantity');
        });
    }
}
