<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseSetting extends Model
{
    protected $table = 'course_settings';
    
    public function course()
    {
        return $this->belongsTo('App\Courses', 'course_id', 'id');
    }

}
