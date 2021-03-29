<?php

namespace Database\Seeds\Custom;

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class Seeder1595224061 extends Seeder
{
    public function run()
    {
        $permissionTooltipsToSeed = array(
            [
                'name' => 'student-docs-upload',
                'category_id' => 5,
                'tooltip_en' => 'Allow user to upload student documents.',
                'tooltip_ja' => 'ユーザーに生徒書類アップロード権限を与える。'
            ],
            [
                'name' => 'student-docs-delete',
                'category_id' => 5,
                'tooltip_en' => 'Allow user to delete student documents.',
                'tooltip_ja' => 'ユーザーに生徒書類削除権限を与える。'
            ],
        );
        foreach ($permissionTooltipsToSeed as $permissionTooltip){
            Permission::create([ 'name' => $permissionTooltip['name'],
                    'category_id' => $permissionTooltip['category_id'],
                    'tooltip_en' => $permissionTooltip['tooltip_en'],
                    'tooltip_ja' => $permissionTooltip['tooltip_ja'],
            ]);
            $roleHasPermissionsToSeed = array(
                'role' => 'Super Admin','permission' => $permissionTooltip['name']
            );
            $role = Role::where('name', $roleHasPermissionsToSeed['role'])->first();
            $role->givePermissionTo($roleHasPermissionsToSeed['permission']);
        }

    }
}