@extends('layouts.app')
@section('title', ' - '. __('messages.courseadd'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @include('partials.success')
            @include('partials.error')
	        <form method="POST" action="{{ route('course.store') }}" enctype="multipart/form-data">
	        	@csrf
	          	<h1>{{ __('messages.courseadd') }}</h1>
	          	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.title') }}</label>
	            	<div class="col-lg-10">
	              		<input name="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}" required="">
	            	</div>
                </div>
	         	<div class="form-group row">
	         		<label class="col-lg-2 col-form-label">{{ __('messages.description') }}</label>
	         		<div class="col-lg-10">
	         			<textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" >{{ old('description') }}</textarea>
	         		</div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.objectives') }}</label>
                    <div class="col-lg-10">
                        <textarea name="objectives" class="form-control{{ $errors->has('objectives') ? ' is-invalid' : '' }}" >{{ old('objectives') }}</textarea>
                    </div>
                </div>
	         	<div class="form-group row">
                    <label for="cost" class="col-lg-2 col-form-label">{{ __('messages.thumbnail') }}</label>

                    <div class="col-lg-10 input-file-wrapper">
                        <div style="display: none" class="preview-section">
                            <img src="#" alt="thumbnail-image"/>
                        </div>
                        <div class="input-section">
                            <input type="file" class="insert-image {{ $errors->has('image') ? 'is-invalid' : '' }}" name="image" accept=".png,.jpg,.jpeg">
                            <small id="fileHelp" class="form-text text-muted">{{ __('messages.acceptfiletypes') }}</small>
                        </div>
                    </div>
                </div>
                @if (count($custom_fields) > 0)
			        @foreach ($custom_fields as $custom_field) 
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}</label>
                        <div class="col-lg-10">
                            <input name="custom_{{ $custom_field->field_name }}" type="text" value="{{ old('custom_'.$custom_field->field_name ) }}" class="form-control{{ $errors->has('custom_'.$custom_field->field_name) ? ' is-invalid' : '' }}" {{ $custom_field->field_required ? 'required' : '' }}>
                        </div>
                    </div>
                    @endforeach
                @endif
	          	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label"></label>
	            	<div class="col-lg-10">
	              		<input name="add" type="submit" value="{{ __('messages.add') }}" class="form-control btn-success">
	            	</div>
	          	</div>
	        </form>
      	</div>
    </div>
@endsection
