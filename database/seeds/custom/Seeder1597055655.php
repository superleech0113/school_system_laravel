<?php

namespace Database\Seeds\Custom;

use App\Helpers\AutomatedTagsHelper;
use App\Students;
use Illuminate\Database\Seeder;

class Seeder1597055655 extends Seeder
{
    public function run()
    {
        foreach(Students::get() as $student)
        {
            $automatedTagsHelper = new AutomatedTagsHelper($student);
            $automatedTagsHelper->refreshLineConnectedTag(true);
        }
    }
}