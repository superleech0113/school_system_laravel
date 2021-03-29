<?php

namespace Database\Seeds\Custom;

use App\Permission;
use App\Role;
use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1595652957 extends Seeder
{
    public function run()
    { 
        $permissionTooltipsToSeed = array(
            [
                'name' => 'application-settings',
                'category_id' => 8,
                'tooltip_en' => 'Allow user to access application settings.',
                'tooltip_ja' => 'ユーザーにアプリケーション設定表示権限を与える。'
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

        $settingsToSeed = [
            'application_docs' => true,
        ];
        foreach($settingsToSeed as $key => $value){
            $setting = new Settings();
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        }
        
    }
}