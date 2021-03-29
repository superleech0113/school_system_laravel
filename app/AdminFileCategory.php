<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminFileCategory extends Model
{
    protected $table = 'admin_file_categories';
    
    public $timestamps = false;

    public function admin_files()
    {
        return $this->hasMany('App\AdminFile','category_id','id');
    }

    public function adminFilesForDropzone()
    {
        $out = array();
        foreach($this->admin_files as $file){
            $out[] = [
                'id' => $file->id,
                'name' => basename($file->file_path)
            ];
        }
        return json_encode($out);
    }

    public function the_files_url()
    {
        $html = '<div class="files-list">';
        foreach($this->admin_files as $file)
        {
            $html.= "<a class=\"mr-3\" href='".htmlspecialchars(tenant_asset($file->file_path),ENT_QUOTES)."' download>".(empty($file->file_name) ? basename($file->file_path) : $file->file_name)."</a>";
            if (!\Auth::user()->hasRole('student')) {
                $html.= "<button data-type='admin_file' data-id='".$file->id."' data-name='".(empty($file->file_name) ? basename($file->file_path) : $file->file_name)."'  class='btn btn-success btn_file_name_edit'><i class='fa fa-pencil'></i></button> 
                <form class=\"delete\" method=\"POST\" action='".route('adminfile.delete', $file->id)."'>
                    ".csrf_field()."
                    <button class=\"btn btn-danger\" type=\"submit\"><i class='fa fa-trash'></i></button>
                </form>";
           }
            $html.=  "<br>";
        }
        return $html."</div>";
    }

}
