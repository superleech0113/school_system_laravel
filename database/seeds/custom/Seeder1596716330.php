<?php

namespace Database\Seeds\Custom;

use App\Tag;
use Illuminate\Database\Seeder;

class Seeder1596716330 extends Seeder
{
    public function run()
    {
        $tagsToSeed = [
            [
                'name' => Tag::STRIPE_SUBSCRIPTION_ERROR,
                'color' => "#FF0000",
                'icon' => "fa-credit-card",
                'is_automated' => 1,
            ]
        ];

        foreach ($tagsToSeed as $record) {
            $tag = new Tag();
            $tag->name = $record['name'];
            $tag->color = $record['color'];
            $tag->icon = $record['icon'];
            $tag->is_automated = $record['is_automated'];
            $tag->save();
        }
    }
}