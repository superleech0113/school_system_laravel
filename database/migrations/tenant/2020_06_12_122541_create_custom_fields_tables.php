<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTables extends Migration
{
    public function up()
    {
        Schema::create( 'custom_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field_name');
            $table->string('field_label_en');
            $table->string('field_label_ja');
            $table->enum('field_type',['text', 'link', 'link-button', 'number', 'checkbox', 'date'])->default('text');
            $table->boolean('field_required')->default(false);
            $table->enum('data_model',['Students', 'Lessons', 'Courses'])->default('Students');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('custom_field_id');
            $table->foreign('custom_field_id')->references('id')->on( 'custom_fields');
            $table->unsignedInteger('model_id');
            $table->string('field_value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('custom_field_responses');
    }
}
