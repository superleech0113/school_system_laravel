<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionStatusFieldToTenantSubscriptionsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            $table->tinyInteger('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            $table->dropColumn('subscription_status');
        });
    }
}
