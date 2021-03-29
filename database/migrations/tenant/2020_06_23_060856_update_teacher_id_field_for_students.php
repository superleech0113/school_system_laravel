<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeacherIdFieldForStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('teacher_id')->change();
        });
        
        Schema::table('students', function(Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function(Blueprint $table) {
            $table->dropForeign('students_teacher_id_foreign');
            $table->dropIndex('students_teacher_id_foreign');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('teacher_id')->change();
        });
    }
}
