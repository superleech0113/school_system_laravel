<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentDoc extends Model
{
    protected $table = 'student_docs';
    
    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo('App\Students', 'student_id', 'id');
    }  
}
