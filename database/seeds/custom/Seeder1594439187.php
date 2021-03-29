<?php

namespace Database\Seeds\Custom;

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class Seeder1594439187 extends Seeder
{
    public function run()
    {
        $permission = Permission::create([
            'name' => 'student-information',
            'guard_name' => 'web',
            'category_id' => 9,
            'tooltip_en' => 'Allow user to edit bulk student information.',
            'tooltip_ja' => 'ユーザーに生徒情報のバルク編集権限を与える。',
        ]);
        
        $role = Role::where('name', 'Super Admin')->first();
        $role->givePermissionTo('student-information');
    }
}