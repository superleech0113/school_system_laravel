@php $custom_field = \App\CustomFields::where('data_model', 'Applications')->where('field_name', $field->field_name)->first(); 
$custom_value = '';
$value = $custom_field->custom_field_values->where('model_id', $application->id)->first(); 
if (!empty($value)) {
    $custom_value = $value->field_value;
}       
@endphp
<div class="form-group row">
    <label class="col-lg-3 col-form-label">{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}</label>
    <div class="col-lg-9">
        <input name="custom_{{ $custom_field->field_name }}" type="text" value="{{ old('custom_'.$custom_field->field_name) ?? $custom_value }}" class="form-control{{ $errors->has('custom_'.$custom_field->field_name) ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
    </div>
</div>