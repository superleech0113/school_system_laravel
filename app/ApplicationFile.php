<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ApplicationFile extends Model
{
    protected $table = 'application_files';
    
    public $timestamps = false;

    public function application()
    {
        return $this->belongsTo('App\Applications', 'application_id', 'id');
    }  
}
