@extends('layouts.app')
@section('title', ' - '. __('messages.editcourse'))

@section('content')
    <div class="row justify-content-center">
        @include('partials.success')
        @include('partials.error')
        <div class="col-12">
          <form method="POST" action="{{ route('course.update', $course->id) }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <h1>{{ __('messages.editcourse') }}</h1>
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">{{ __('messages.title') }}</label>
                  <div class="col-lg-10">
                      <input name="title" type="text" value="{{empty(old('title')) ? $course->title : old('title')}}" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" required="">
                  </div>
                </div>
                <div class="form-group row">
	         		<label class="col-lg-2 col-form-label">{{ __('messages.description') }}</label>
	         		<div class="col-lg-10">
	         			<textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" >{{empty(old('description')) ? $course->description : old('description')}}</textarea>
	         		</div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.objectives') }}</label>
                    <div class="col-lg-10">
                        <textarea name="objectives" class="form-control{{ $errors->has('objectives') ? ' is-invalid' : '' }}" >{{ old('objectives',$course->objectives) }}</textarea>
                    </div>
                </div>
	         	<div class="form-group row">
                    <label for="cost" class="col-lg-2 col-form-label">{{ __('messages.thumbnail') }}</label>
                    <div class="col-lg-10 input-file-wrapper">
                    	@if(!$course->thumbnail)
                    	<div style="display: none" class="preview-section">
                    	@else
                    	<div class="preview-section">
                    	@endif
                            {!! $course->the_image() !!}
                        </div>
                        <div class="input-section">
                            <input type="file" class="insert-image {{ $errors->has('image') ? 'is-invalid' : '' }}" name="image" accept=".png,.jpg,.jpeg">
                            <small id="fileHelp" class="form-text text-muted">{{ __('messages.acceptfiletypes') }}</small>
                            <input type="hidden" name="update_image" value="false" class="file-update">
                        </div>
                    </div>
                </div>
                @if (count($custom_fields) > 0)
			        @foreach ($custom_fields as $custom_field) 
                        @php 
                            $custom_value = '';
                            $value = $custom_field->custom_field_values->where('model_id', $course->id)->first(); 
                            if (!empty($value)) {
                                $custom_value = $value->field_value;
                            }
                        @endphp
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}</label>
                        <div class="col-lg-10">
                            <input name="custom_{{ $custom_field->field_name }}" type="text" value="{{ old('custom_'.$custom_field->field_name) ?? $custom_value }}" class="form-control{{ $errors->has('custom_'.$custom_field->field_name) ? ' is-invalid' : '' }}" {{ $custom_field->field_required ? 'required' : '' }}>
                        </div>
                    </div>
                    @endforeach
                @endif
	          	
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label"></label>
                  <div class="col-lg-10">
                      <input name="edit" type="submit" value="{{ __('messages.edit') }}" class="btn btn-success form-control">
                  </div>
                </div>
            </div>
          </form>
      </div>
    </div>
@endsection
