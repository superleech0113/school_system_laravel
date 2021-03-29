<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('firstname')->nullable()->change();
            $table->string('lastname')->nullable()->change();
            $table->string('firstname_kanji')->nullable()->change();
            $table->string('lastname_kanji')->nullable()->change();
            $table->string('firstname_furigana')->nullable()->change();
            $table->string('lastname_furigana')->nullable()->change();
            $table->string('mobile_phone')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->text('toiawase_referral')->nullable()->change();
            $table->text('toiawase_houhou')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('firstname')->nullable(false)->change();
            $table->string('lastname')->nullable(false)->change();
            $table->string('firstname_kanji')->nullable(false)->change();
            $table->string('lastname_kanji')->nullable(false)->change();
            $table->string('firstname_furigana')->nullable(false)->change();
            $table->string('lastname_furigana')->nullable(false)->change();
            $table->string('mobile_phone')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->text('toiawase_referral')->nullable(false)->change();
            $table->text('toiawase_houhou')->nullable(false)->change();
        });
    }
}
