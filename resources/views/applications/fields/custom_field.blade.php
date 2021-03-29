@php $custom_field = \App\CustomFields::where('data_model', 'Applications')->where('field_name', $field->field_name)->first(); @endphp
<div class="col-lg-6">
    <div class="form-group">
        <label class="col-form-label">{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}</label>
        <div >
            <input name="custom_{{ $custom_field->field_name }}" type="text" value="{{ old('custom_'.$custom_field->field_name ) }}" class="form-control{{ $errors->has('custom_'.$custom_field->field_name) ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
        </div>
    </div>
</div>
	