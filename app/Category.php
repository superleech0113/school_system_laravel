<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'categories';

    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];

    public function permissions()
    {
        return $this->hasMany('App\Permission', 'category_id', 'id');
    }

}
