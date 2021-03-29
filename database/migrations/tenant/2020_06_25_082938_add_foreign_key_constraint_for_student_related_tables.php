<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintForStudentRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function(Blueprint $table) {
            $table->unsignedInteger('customer_id')->change();
        });
        Schema::table('attendances', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('students')->onDelete('cascade');
        });
        Schema::table('checkins', function(Blueprint $table) {
            $table->unsignedInteger('student_id')->change();
        });
        Schema::table('checkins', function(Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
        Schema::table('class_usages', function(Blueprint $table) {
            $table->unsignedInteger('customer_id')->change();
        });
        Schema::table('class_usages', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('students')->onDelete('cascade');
        });
        Schema::table('class_usage_summaries', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('students')->onDelete('cascade');
        });
        Schema::table('contacts', function(Blueprint $table) {
            $table->unsignedInteger('customer_id')->change();
        });
        Schema::table('contacts', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('students')->onDelete('cascade');
        });
        Schema::table('monthly_payments', function(Blueprint $table) {
            $table->unsignedInteger('customer_id')->change();
        });
        Schema::table('monthly_payments', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('students')->onDelete('cascade');
        });
        Schema::table('payments', function(Blueprint $table) {
            $table->unsignedInteger('customer_id')->change();
        });
        Schema::table('payments', function(Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function(Blueprint $table) {
            $table->dropForeign('attendances_customer_id_foreign');
            $table->dropIndex('attendances_customer_id_foreign');
        });
        Schema::table('attendances', function(Blueprint $table) {
            $table->integer('customer_id')->change();
        });
        Schema::table('checkins', function(Blueprint $table) {
            $table->dropForeign('checkins_student_id_foreign');
            $table->dropIndex('checkins_student_id_foreign');
        });
        Schema::table('checkins', function(Blueprint $table) {
            $table->integer('student_id')->change();
        });
        Schema::table('class_usages', function(Blueprint $table) {
            $table->dropForeign('class_usages_customer_id_foreign');
            $table->dropIndex('class_usages_customer_id_foreign');
        });
        Schema::table('class_usages', function(Blueprint $table) {
            $table->integer('customer_id')->change();
        });
        Schema::table('class_usage_summaries', function(Blueprint $table) {
            $table->dropForeign('class_usage_summaries_customer_id_foreign');
        });
        Schema::table('contacts', function(Blueprint $table) {
            $table->dropForeign('contacts_customer_id_foreign');
            $table->dropIndex('contacts_customer_id_foreign');
        });
        Schema::table('contacts', function(Blueprint $table) {
            $table->integer('customer_id')->change();
        });
        Schema::table('monthly_payments', function(Blueprint $table) {
            $table->dropForeign('monthly_payments_customer_id_foreign');
            $table->dropIndex('monthly_payments_customer_id_foreign');
        });
        Schema::table('monthly_payments', function(Blueprint $table) {
            $table->integer('customer_id')->change();
        });
        Schema::table('payments', function(Blueprint $table) {
            $table->dropForeign('payments_customer_id_foreign');
            $table->dropIndex('payments_customer_id_foreign');
        });
        Schema::table('payments', function(Blueprint $table) {
            $table->integer('customer_id')->change();
        });
    }
}
