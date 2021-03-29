<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFields extends Model
{
	use SoftDeletes;
	protected $table = 'custom_fields';
	
	public const FIELD_TYPE = [
		'text', 'link', 'link-button' //, 'number', 'checkbox', 'date'
	];
	
	public const DATA_MODEL = [
		'Students', 'Lessons', 'Courses', 'Teachers', 'Applications'
	];
    
  	protected $fillable = [
    	'field_name',
    	'field_label_en',
    	'field_label_ja',
    	'field_type',
    	'field_required',
    	'data_model'
	  ];
	
	public function custom_field_values()
    {
        return $this->hasMany('App\CustomFieldValue', 'custom_field_id', 'id');
    }

  

}
