<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormOrders extends Model
{
	use SoftDeletes;
	protected $table = 'form_orders';
	
	public const DATA_MODEL = [
		//'Students', 'Lessons', 'Courses', 'Teachers',
		 'Applications'
	];
    public const DATA_MODEL_FOLDER = [
		'Students' => 'student', 'Lessons' => 'course/unit/lesson', 'Courses' => 'courses', 'Teachers' => 'teacher', 'Applications' => 'applications'
	];
	
	public const EXCLUDE_FIELDS = [
		'Students' => 'student', 'Lessons' => 'course/unit/lesson', 'Courses' => 'courses', 'Teachers' => 'teacher', 
		'Applications' => ['email']
	];
    
  	protected $fillable = [
        'field_name',
    	'sort_order',
    	'is_visible',
		'data_model',
		'is_custom',
		'is_required'
	  ];
}
