<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTooltipColPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE permissions MODIFY tooltip_en  TEXT;');
        DB::statement('ALTER TABLE permissions MODIFY tooltip_ja  TEXT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE permissions MODIFY tooltip_en  VARCHAR(191);');
        DB::statement('ALTER TABLE permissions MODIFY tooltip_ja  VARCHAR(191);');
    }
}
