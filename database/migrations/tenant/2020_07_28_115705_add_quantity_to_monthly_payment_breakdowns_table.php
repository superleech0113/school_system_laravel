<?php

use App\Helpers\DatabaseAdjustment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToMonthlyPaymentBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_payment_breakdowns', function (Blueprint $table) {
            $table->integer('quantity')->after('description');
            $table->renameColumn('amount','unit_amount');
        });

        DatabaseAdjustment::migrateMonthlyPayments();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_payment_breakdowns', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->renameColumn('unit_amount', 'amount');
        });
    }
}
