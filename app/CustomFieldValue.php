<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
	protected $table = 'custom_field_values';
    
  	protected $fillable = [
    	'custom_field_id',
    	'model_id',
    	'field_value'
      ];
      
    public function field()
    {
        return $this->belongsTo(CustomFields::class, 'custom_field_id');
    }
}
