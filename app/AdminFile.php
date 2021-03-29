<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminFile extends Model
{
    protected $table = 'admin_files';
    
    public $timestamps = false;

    public function admin_file_category()
    {
        return $this->belongsTo('App\AdminFileCategory','category_id','id');
    }

    public function the_file_url()
    {
        $html = '<div class="files-list">';
        $html .= "<a class=\"mr-3\" href='".htmlspecialchars(tenant_asset($this->file_path),ENT_QUOTES)."' download>".(empty($this->file_name) ? basename($this->file_path) : $this->file_name)."</a>";
        if (!\Auth::user()->hasRole('student')) {
            $html.= "<button data-type='admin_file' data-id='".$this->id."' data-name='".(empty($this->file_name) ? basename($this->file_path) : $this->file_name)."'  class='btn btn-success btn_file_name_edit'><i class='fa fa-pencil'></i></button> 
            <form class=\"delete\" method=\"POST\" action='".route('adminfile.delete', $this->id)."'>
                ".csrf_field()."
                <button class=\"btn btn-danger\" type=\"submit\"><i class='fa fa-trash'></i></button>
            </form>";
        }
        $html .=  "<br>";
        return $html."</div>";
    }
}
