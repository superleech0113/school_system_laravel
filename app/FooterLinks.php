<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FooterLinks extends Model
{
    protected $table = 'footer_links';

    protected $fillable = [
        'label_en', 'label_ja', 'link', 'display_order'
    ];

}
