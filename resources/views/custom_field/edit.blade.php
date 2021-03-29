@extends('layouts.app')
@section('title', ' - '. __('messages.editcustomfield'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @include('partials.success')
            @include('partials.error')
	        <form method="POST" action="{{ route('custom-field.update', $custom_field->id) }}">
                @method('PATCH')
                @csrf
	          	<h1>{{ __('messages.editcustomfield') }}</h1>
	          	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.fieldlabel_en') }}</label>
	            	<div class="col-lg-10">
	              		<input name="field_label_en" type="text" class="form-control{{ $errors->has('field_label_en') ? ' is-invalid' : '' }}" value="{{ old('field_label_en') ?? $custom_field->field_label_en }}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.fieldlabel_ja') }}</label>
	            	<div class="col-lg-10">
	              		<input name="field_label_ja" type="text" class="form-control{{ $errors->has('field_label_ja') ? ' is-invalid' : '' }}" value="{{ old('field_label_ja') ?? $custom_field->field_label_ja }}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.fieldname') }}</label>
	            	<div class="col-lg-10">
	              		<input name="field_name" type="text" class="form-control{{ $errors->has('field_name') ? ' is-invalid' : '' }}" value="{{ old('field_name') ?? $custom_field->field_name }}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.type') }}</label>
                    <div class="col-lg-10">
                        <select name="field_type" class="form-control{{ $errors->has('field_type') ? ' is-invalid' : '' }}" required>
                            <option value="">{{ __('messages.select-type') }}</option>
                            @foreach(\App\CustomFields::FIELD_TYPE as $field_type)
                                <option value="{{ $field_type }}" @if($field_type == (old('field_type') ?? $custom_field->field_type)) selected @endif>{{ $field_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.required') }}</label>
                    <div class="col-lg-10">
                        <select name="field_required" class="form-control{{ $errors->has('field_required') ? ' is-invalid' : '' }}" required>
                            <option value="">{{ __('messages.select-required') }}</option>
                            <option value="1" @if('1' == (old('field_required') ?? $custom_field->field_required)) selected @endif>True</option>
                            <option value="0" @if('0' == (old('field_required') ?? $custom_field->field_required)) selected @endif>False</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.model') }}</label>
                    <div class="col-lg-10">
                        <select name="data_model" class="form-control{{ $errors->has('data_model') ? ' is-invalid' : '' }}" required>
                            <option value="">{{ __('messages.select-model') }}</option>
                            @foreach(\App\CustomFields::DATA_MODEL as $data_model)
                                <option value="{{ $data_model }}" {{ (old('data_model') ?? $custom_field->data_model) == $data_model ? 'selected' : ''}} >{{ $data_model }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
	          	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label"></label>
	            	<div class="col-lg-10">
	              		<input name="add" type="submit" value="{{ __('messages.edit') }}" class="form-control btn-success">
	            	</div>
	          	</div>
	        </form>
      	</div>
    </div>
@endsection
