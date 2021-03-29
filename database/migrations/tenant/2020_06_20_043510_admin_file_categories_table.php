<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminFileCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_file_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('admin_files', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('admin_file_categories')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_files', function (Blueprint $table) {
            $table->dropForeign('admin_files_category_id_foreign');
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('admin_file_categories');
    }
}