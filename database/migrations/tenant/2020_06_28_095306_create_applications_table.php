<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('application_no');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('firstname_kanji');
            $table->string('lastname_kanji');
            $table->string('firstname_furigana');
            $table->string('lastname_furigana');
            $table->tinyInteger('status')->default(0);
            $table->date('join_date')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('mobile_phone');
            $table->string('email');
            $table->string('address');
            $table->text('toiawase_referral');
            $table->text('toiawase_memo')->nullable();
            $table->text('toiawase_houhou');
            $table->date('toiawase_date')->nullable();
            $table->date('birthday')->nullable();
            $table->string('image')->nullable();
            $table->string('levels', 191)->nullable();
            $table->string('lang')->nullable();
           
            $table->string('office_name')->nullable();
            $table->string('office_address')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_address')->nullable();
            $table->string('school_phone')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
         
            $table->timestamps();
        });

        Schema::create('application_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('application_id');
            $table->text('file_path');
            $table->string('file_name');
       
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
        \DB::statement("ALTER TABLE custom_fields CHANGE COLUMN data_model data_model ENUM('Students', 'Lessons', 'Courses', 'Teachers', 'Applications') NOT NULL DEFAULT 'Students'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_files');
        Schema::dropIfExists('applications');
        \DB::statement("ALTER TABLE custom_fields CHANGE COLUMN data_model data_model ENUM('Students', 'Lessons', 'Courses', 'Teachers') NOT NULL DEFAULT 'Students'");
    }
}
