<?php

namespace App\Helpers;

use App\Settings;
use Carbon\Carbon;

class StripeHelper {

    public function getFirstInvoiceTime()
    {
        $subscription_starts_at = Carbon::now(CommonHelper::getSchoolTimezone())->firstOfMonth()->addDays(Settings::get_value('subscription_billing_day') - 1);
        if (Carbon::now(CommonHelper::getSchoolTimezone()) > $subscription_starts_at) {
            $subscription_starts_at = $subscription_starts_at->addMonth();
        }
        return $subscription_starts_at;
    }
}