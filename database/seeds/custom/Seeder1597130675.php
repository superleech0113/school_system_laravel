<?php

namespace Database\Seeds\Custom;

use App\Helpers\DatabaseAdjustment;
use Illuminate\Database\Seeder;

class Seeder1597130675 extends Seeder
{
    public function run()
    {
        DatabaseAdjustment::removeRedundantYoakusForSchoolOffDays();
        DatabaseAdjustment::removeRedundantYoakusForClassOffDays();
    }
}