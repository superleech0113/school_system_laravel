<?php

namespace Database\Seeds\Custom;

use App\Category;
use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class Seeder1594878951 extends Seeder
{
    public function run()
    {
        $permissionsToSeed = [
            [
                'name' => 'pwa-settings',
                'category' => 'settings',
                'tooltip_en' => 'Allow user to update PWA settings.',
                'tooltip_ja' => 'ユーザーにPWA設定編集権限を与える。',
            ]
        ];

        foreach ($permissionsToSeed as $record) {
            Permission::create([
                'name' => $record['name'],
                'category_id' => Category::where('name', $record['category'])->first()->id,
                'tooltip_en' => $record['tooltip_en'],
                'tooltip_ja' => $record['tooltip_ja'],
            ]);

            $role = Role::where('name', 'Super Admin')->first();
            $role->givePermissionTo($record['name']);
        }
    }
}
