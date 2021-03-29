<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyPaymentBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_payment_breakdowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('monthly_payment_id');
            $table->unsignedBigInteger('plan_id')->nullable()->default(NULL);
            $table->text('description')->nullable()->default(NULL);
            $table->double('amount')->nullable()->default(NULL);
        });

        Schema::table('monthly_payment_breakdowns', function(Blueprint $table) {
            $table->foreign('monthly_payment_id')->references('id')->on('monthly_payments')->onDelete('cascade');
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
        Schema::table('monthly_payment_breakdowns', function(Blueprint $table) {
            $table->dropForeign('monthly_payment_breakdowns_monthly_payment_id_foreign');
            $table->dropIndex('monthly_payment_breakdowns_monthly_payment_id_foreign');
            $table->dropForeign('monthly_payment_breakdowns_plan_id_foreign');
            $table->dropIndex('monthly_payment_breakdowns_plan_id_foreign');
        });
        Schema::dropIfExists('monthly_payment_breakdowns');
    }
}
