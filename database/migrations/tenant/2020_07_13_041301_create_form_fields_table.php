<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('field_name');
            $table->integer('sort_order');
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_required')->default(false);
            $table->enum('data_model',['Students', 'Lessons', 'Courses', 'Teachers', 'Applications'])->default('Students');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_orders');
    }
}
