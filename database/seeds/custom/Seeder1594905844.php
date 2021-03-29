<?php

namespace Database\Seeds\Custom;

use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1594905844 extends Seeder
{
    public function run()
    {
        $settingsToSeed = [
            'pwa_data' => NULL,
            'pwa_status' => 0,
        ];
        foreach($settingsToSeed as $key => $value) {
            $setting = new Settings();
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        }
    }
}