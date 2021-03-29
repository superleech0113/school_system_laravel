<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPwaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pwa_settings');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('pwa_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tenant_id', 36);
            $table->json('data')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }
}
