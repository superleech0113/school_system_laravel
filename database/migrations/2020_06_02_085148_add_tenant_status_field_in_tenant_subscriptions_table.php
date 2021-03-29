<?php

use App\TenantSubscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTenantStatusFieldInTenantSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            $table->tinyInteger('status')->default(TenantSubscription::TENANT_NOT_CREATED);
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
            $table->dropColumn('status');
        });
    }
}
