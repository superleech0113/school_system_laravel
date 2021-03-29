<?php

namespace Database\Seeds\Custom;

use App\CustomFields;
use App\Permission;
use App\Role;
use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1595063024 extends Seeder
{
    public function run()
    {
        $custom_fields = CustomFields::where('data_model', 'Students')->get();
        foreach ($custom_fields as $custom_field) {
            CustomFields::create([
                'field_name' => $custom_field->field_name,
                'field_label_en' => $custom_field->field_label_en,
                'field_label_ja' => $custom_field->field_label_ja,
                'field_type' => $custom_field->field_type,
                'field_required' => $custom_field->field_required,
                'data_model' => 'Applications'
            ]);
        }
        $settingsToSeed = [
            'application_instructions_en' => '',
            'application_instructions_ja' => '',
            'application_doc_instructions_en' => '',
            'application_doc_instructions_ja' => '',
        ];
        foreach($settingsToSeed as $key => $value){
            $setting = new Settings();
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        } 
        $permissionTooltipsToSeed = array(
            [
                'name' => 'student-docs',
                'category_id' => 5,
                'tooltip_en' => 'Allow user to view student documents.',
                'tooltip_ja' => 'ユーザーに生徒書類表示権限を与える。'
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