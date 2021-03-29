<?php

namespace Database\Seeds\Custom;

use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1595566055 extends Seeder
{
    public function run()
    {
        $settingsToSeed = [
            'application_bottom_instructions_en' => '',
            'application_bottom_instructions_ja' => '',
        ];
        foreach($settingsToSeed as $key => $value){
            $setting = new Settings();
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        } 
    }
}