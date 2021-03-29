<?php

namespace App\Http\Requests;

use App\CustomFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomFieldRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->route('custom_field');
        if($id)
        {
            return [
                'field_name' => 'required|uniqueFiledandModel:'.$this->data_model.','.$id.'|max:30',
                'field_label_en' => 'required|max:191',
                'field_label_ja' => 'required|max:191',
                'field_type' => 'in:text,link,link-button,number,checkbox,date|max:191',
                'field_required' => 'in:1,0',
                'data_model' => Rule::in(CustomFields::DATA_MODEL).'|max:191',
            ];
        }
        else
        {
            return [
                'field_name' => 'required|uniqueFiledandModel:{$request->data_model}|max:30',
                'field_label_en' => 'required|max:191',
                'field_label_ja' => 'required|max:191',
                'field_type' => 'in:text,link,link-button,number,checkbox,date|max:191',
                'field_required' => 'in:1,0',
                'data_model' => Rule::in(CustomFields::DATA_MODEL).'|max:191',
            ];
        }
    }
}
