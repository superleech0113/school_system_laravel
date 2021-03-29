<?php

namespace App\Http\Requests;

use App\FormOrders;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function rules()
    {
        $fields = FormOrders::where('data_model', 'Applications')->where('is_visible', true)->orderBy('sort_order')->get();
        
        $validate_params = [
            'image' => 'mimes:jpeg,jpg,png|max:2000'
        ];
        foreach ($fields as $field) {
            if ($field->is_required ) {
                if ($field->field_name == 'image') {
                    $validate_params['image'] = 'required|mimes:jpeg,jpg,png|max:2000';
                } else {
                    $validate_params[$field->field_name] = 'required';
                }
            } else if($field->field_name == 'image') {
                $validate_params['image'] = 'mimes:jpeg,jpg,png|max:2000';
            }
        }

        if($this->route('application')) {
            $validate_params['email'] = 'required|unique:applications,email,'.$this->route('application');
        } else {
            $validate_params['email'] = 'required|unique:applications,email';
        }

        return $validate_params;
    }
}
