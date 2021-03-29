<?php

namespace Database\Seeds\Custom;

use App\FormOrders;
use Illuminate\Database\Seeder;

class Seeder1594965050 extends Seeder
{
    public function run()
    {
        FormOrders::truncate();
        
        $formOrdersToSeed = array(
            [
                'field_name' => 'lastname',
                'sort_order' => 1,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'firstname',
                'sort_order' => 2,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'lastname_kanji',
                'sort_order' => 3,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'firstname_kanji',
                'sort_order' => 4,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'lastname_furigana',
                'sort_order' => 5,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'firstname_furigana',
                'sort_order' => 6,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'email',
                'sort_order' => 7,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'join_date',
                'sort_order' => 9,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
            [
                'field_name' => 'birthday',
                'sort_order' => 10,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
            [
                'field_name' => 'address',
                'sort_order' => 11,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'home_phone',
                'sort_order' => 12,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
            [
                'field_name' => 'mobile_phone',
                'sort_order' => 13,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'toiawase_referral',
                'sort_order' => 14,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
            [
                'field_name' => 'levels',
                'sort_order' => 15,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
            [
                'field_name' => 'office_name',
                'sort_order' => 16,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
			[
                'field_name' => 'office_address',
                'sort_order' => 17,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
            [
                'field_name' => 'office_phone',
                'sort_order' => 18,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
			 [
                'field_name' => 'school_name',
                'sort_order' => 19,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
			 [
                'field_name' => 'school_address',
                'sort_order' => 20,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
			 [
                'field_name' => 'school_phone',
                'sort_order' => 21,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
			 [
                'field_name' => 'toiawase_houhou',
                'sort_order' => 22,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => true
            ],
			 [
                'field_name' => 'toiawase_memo',
                'sort_order' => 23,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
			 [
                'field_name' => 'image',
                'sort_order' => 24,
                'is_visible' => true,
                'data_model' => 'Applications',
                'created_at' => date('Y-m-d H:i:s'),
                'is_custom' => false,
                'is_required' => false
            ],
        );
        foreach ($formOrdersToSeed as $formOrder){
            FormOrders::create($formOrder);
        }

    }
}