<?php

use App\Helpers\DatabaseAdjustment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPaymentSettingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DatabaseAdjustment::migratePaymentSettings();

        Schema::table('payment_settings', function(Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('no_of_lessons');
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
            $table->double('price')->default(NULL)->nullable();
            $table->integer('no_of_lessons')->default(NULL)->nullable();
        });
    }
}
