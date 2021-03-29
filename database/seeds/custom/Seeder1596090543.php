<?php

namespace Database\Seeds\Custom;

use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1596090543 extends Seeder
{
    public function run()
    {
        $settingsToSeed = [
            'use_stripe_subscription' => 0,
            'subscription_billing_day' => null
        ];
        foreach ($settingsToSeed as $key => $value) {
            $setting = new Settings();
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        }
    }
}
