<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentBreakdownSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_breakdown_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('student_id');
            $table->unsignedBigInteger('plan_id')->nullable()->default(NULL);
            $table->text('description')->nullable()->default(NULL);
            $table->double('amount')->nullable()->default(NULL);
        });

        Schema::table('payment_breakdown_settings', function(Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
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
        Schema::table('payment_breakdown_settings', function(Blueprint $table) {
            $table->dropForeign('payment_breakdown_settings_student_id_foreign');
            $table->dropIndex('payment_breakdown_settings_student_id_foreign');
            $table->dropForeign('payment_breakdown_settings_plan_id_foreign');
            $table->dropIndex('payment_breakdown_settings_plan_id_foreign');
        });

        Schema::dropIfExists('payment_breakdown_settings');
    }
}
