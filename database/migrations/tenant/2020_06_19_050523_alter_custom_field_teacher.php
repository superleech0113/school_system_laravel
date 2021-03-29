<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomFieldTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE custom_fields CHANGE COLUMN data_model data_model ENUM('Students', 'Lessons', 'Courses', 'Teachers') NOT NULL DEFAULT 'Students'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE custom_fields CHANGE COLUMN data_model data_model ENUM('Students', 'Lessons', 'Courses') NOT NULL DEFAULT 'Students'");
    }
}
