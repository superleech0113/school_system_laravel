<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstrainsForUserRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
        });
        Schema::table('activity_logs', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_logs', function(Blueprint $table) {
            $table->dropForeign('activity_logs_user_id_foreign');
            $table->dropIndex('activity_logs_user_id_foreign');
        });
        Schema::table('activity_logs', function(Blueprint $table) {
            $table->integer('user_id')->change();
        });
    }
}
