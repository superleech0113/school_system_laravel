<?php

namespace Database\Seeds\Custom;

use App\Category;
use App\Permission;
use Illuminate\Database\Seeder;

class Seeder1593838380 extends Seeder
{
    public function run()
    {
        $categoriesToSeed = array(
            'student-views',
        );

        foreach($categoriesToSeed as $category) {
            Category::create([ 'name' => $category ]);
        }

        $permissionCategoriesToUpdate  = [
            [
                'permission' => 'st-classes',
                'category' => 'student-views'
            ],
            [
                'permission' => 'st-class-details',
                'category' => 'student-views'
            ],
            [
                'permission' => 'class-usage',
                'category' => 'student-views'
            ],
            [
                'permission' => 'st-payments',
                'category' => 'student-views'
            ],
            [
                'permission' => 'calendar',
                'category' => 'student-views'
            ],
            [
                'permission' => 'calendar-hide-full-class',
                'category' => 'student-views'
            ],
            [
                'permission' => 'reservation-list',
                'category' => 'student-views'
            ],
            [
                'permission' => 'children',
                'category' => 'student-views'
            ],
            [
                'permission' => 'take-student-test',
                'category' => 'student-views'
            ],
            [
                'permission' => 'take-assessment',
                'category' => 'student-views'
            ],
            [
                'permission' => 'st-assessments',
                'category' => 'student-views'
            ],
            [
                'permission' => 'assessment',
                'category' => 'assessment'
            ],
            [
                'permission' => 'edit-assessment-response',
                'category' => 'assessment'
            ],
            [
                'permission' => 'test',
                'category' => 'assessment'
            ],
            [
                'permission' => 'manage-availability-timeslots',
                'category' => 'availability'
            ],
            [
                'permission' => 'view-availability-responses',
                'category' => 'availability'
            ]
        ];

        foreach($permissionCategoriesToUpdate as $record) {
            $category_id = Category::where('name', $record['category'])->first()->id;
            Permission::where('name', $record['permission'])->update([
                'category_id' => $category_id
            ]);
        }
    }
}