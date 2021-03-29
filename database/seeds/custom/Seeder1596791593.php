<?php

namespace Database\Seeds\Custom;

use App\Category;
use App\Permission;
use App\Role;
use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1596791593 extends Seeder
{
    public function run()
    {
        $permissionsToDelete = [
            'view-plans', 'create-plan', 'edit-plan', 'delete-plan',
            'view-discounts', 'create-discount', 'edit-discount', 'delete-discount'
        ];
        Permission::whereIn('name', $permissionsToDelete)->delete();

        $use_stripe = Settings::get_value('use_stripe');

        $permissionsToSeed = [
            [
                'name' => 'stripe-subscription-sd-list',
                'category_name' => 'student',
                'tooltip_en' => 'Allow user to view student\'s stripe subscriptions.',
                'tooltip_ja' => 'ユーザーに生徒のカードサブスクリプションを表示する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'stripe-subscription-sd-create',
                'category_name' => 'student',
                'tooltip_en' => 'Allow user to create stripe subscription for students.',
                'tooltip_ja' => 'ユーザーに生徒のカードサブスクリプションを作成する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'stripe-subscription-sd-edit',
                'category_name' => 'student',
                'tooltip_en' => 'Allow user to update student\'s stripe subscriptions.',
                'tooltip_ja' => 'ユーザーに生徒のカードサブスクリプションを編集する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'plan-list',
                'category_name' => 'payment',
                'tooltip_en' => 'Allow user to view plans.',
                'tooltip_ja' => 'ユーザーにサブスクリプションプランを表示する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'plan-create',
                'category_name' => 'payment',
                'tooltip_en' => 'Allow user to create plans.',
                'tooltip_ja' => 'ユーザーにサブスクリプションプランを作成する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'plan-edit',
                'category_name' => 'payment',
                'tooltip_en' => 'Allow user to update plans.',
                'tooltip_ja' => 'ユーザーにサブスクリプションプランを編集する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'discount-list',
                'category_name' => 'payment',
                'tooltip_en' => 'Allow user to view discounts.',
                'tooltip_ja' => 'ユーザーにサブスクリプション割引を表示する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'discount-create',
                'category_name' => 'payment',
                'tooltip_en' => 'Allow user to create discounts.',
                'tooltip_ja' => 'ユーザーにサブスクリプション割引を作成する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'discount-edit',
                'category_name' => 'payment',
                'tooltip_en' => 'Allow user to update discounts.',
                'tooltip_ja' => 'Aユーザーにサブスクリプション割引を編集する権限を与える。',
                'default_roles' => ['Super admin']
            ],
            [
                'name' => 'stripe-subscription-list',
                'category_name' => 'student-views',
                'tooltip_en' => 'Allow user to view their subscriptions.',
                'tooltip_ja' => 'ユーザーにサブスクリプションを表示する権限を与える。',
                'default_roles' => $use_stripe ? ['Student'] : []
            ],
            [
                'name' => 'card-list',
                'category_name' => 'student-views',
                'tooltip_en' => 'Allow user to view their cards.',
                'tooltip_ja' => 'ユーザーにカード情報を表示する権限を与える。',
                'default_roles' => $use_stripe ? ['Student'] : []
            ],
            [
                'name' => 'card-create',
                'category_name' => 'student-views',
                'tooltip_en' => 'Allow user to add card.',
                'tooltip_ja' => 'ユーザーにカードを追加する権限を与える。',
                'default_roles' => $use_stripe ? ['Student'] : []
            ],
            [
                'name' => 'card-delete',
                'category_name' => 'student-views',
                'tooltip_en' => 'Allow user to delete card.',
                'tooltip_ja' => 'ユーザーにサブスクリプション割引を削除する権限を与える。',
                'default_roles' => $use_stripe ? ['Student'] : []
            ],
        ];

       
        foreach($permissionsToSeed as $record) 
        {
            Permission::create([
                'name' => $record['name'],
                'category_id' => Category::where('name', $record['category_name'])->first()->id,
                'tooltip_en' => $record['tooltip_en'],
                'tooltip_ja' => $record['tooltip_ja'],
            ]);

            foreach($record['default_roles'] as $roleName)
            {
                $superAdminRole = Role::where('name', $roleName)->first();
                $superAdminRole->givePermissionTo($record['name']);
            }
        }
    }
}