<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Applications extends Model
{
    protected $table = 'applications';
    
  	protected $fillable = [
        'application_no',
  	 	'firstname',
    	'lastname',
    	'firstname_kanji',
    	'lastname_kanji',
    	'firstname_furigana',
    	'lastname_furigana',
    	'status',
    	'join_date',
    	'home_phone',
    	'mobile_phone',
    	'email',
    	'address',
    	'toiawase_referral',
    	'toiawase_houhou',
    	'toiawase_date',
    	'birthday',
    	'toiawase_memo',
        'levels',
        'office_name',
        'office_address',
        'office_phone',
        'school_name',
        'school_address',
        'school_phone',
        'lang',
        'student_id'
   	];

    public function get_lang()
    {
        return $this->lang;
    }

    public function docs()
    {
        return $this->hasMany('App\ApplicationFile','application_id','id');
    }
   
    public function student()
    {
        return $this->belongsTo('App\Students', 'student_id', 'id');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var    bool
     */
    public $timestamps = false;

  	public function get_kanji_name() {
  	    return $this->firstname_kanji.$this->lastname_kanji;
    }

    public function getFullNameAttribute()
    {
        $locale = \App::getLocale();
        if($locale == 'ja')
        {
            return $this->firstname_kanji.' '.$this->lastname_kanji . ' ('.$this->firstname.' '.$this->lastname.')';
        }
        else
        {
            return $this->firstname.' '.$this->lastname;
        }
    }

    public function getfullNameForEmail($locale)
    {
        if($locale == 'ja')
        {
            return $this->lastname_kanji.' '.$this->firstname_kanji;
        }
        else
        {
            return $this->firstname.' '.$this->lastname;
        }
    }

    public function uploadedImageDetails()
    {
        if($this->image && Storage::disk('public')->has('applications/'.$this->image))
        {
            $out = array();
            $out['upload']['filename'] = $this->image;
            $out['name'] = basename($this->image);
            $out['size'] = Storage::disk('public')->size('applications/'.$this->image);
            $out['url'] = tenant_asset('applications/'.$this->image);
            return json_encode($out);
        }
        else
        {
            return '';
        }
    }
  
    public function getImageUrl()
    {
        return tenant_asset('applications/'.$this->image);
    }

    public function getEmailAddress()
    {
        return $this->email;
    }

    public function the_docs_url()
    {
        $html = '<div class="files-list">';
        foreach($this->docs as $doc)
        {
            $html.= "<a href='".htmlspecialchars(tenant_asset($doc->file_path),ENT_QUOTES)."' target='_blank'>".(empty($doc->file_name) ? basename($doc->file_path) : $doc->file_name)."</a>";
            $html.= "<button data-type='application' data-id='".$doc->id."' data-name='".(empty($doc->file_name) ? basename($doc->file_path) : $doc->file_name)."' class='btn btn-defualt btn_file_name_edit'><i class='fa fa-pencil'></i></button>";
            $html.=  "<br>";
        }
        return $html."</div>";
    }

}
