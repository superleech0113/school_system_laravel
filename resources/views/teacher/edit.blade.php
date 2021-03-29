@extends('layouts.app')
@section('title', ' - '. __('messages.editteacher'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
        	@if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}  
                </div><br/>
            @endif
        	@if($errors->any())
		      	<div class="alert alert-danger">
		        	<ul>
		            	@foreach($errors->all() as $error)
		              		<li>{{ $error }}</li>
		            	@endforeach
		        	</ul>
		      	</div><br/>
		    @endif
	        <form method="POST" action="{{ route('teacher.update', $teacher->id) }}">
	        	@method('PATCH')
	        	@csrf
	        	<h1>{{ __('messages.editteacher') }}</h1>
              	<div class="form-group row">
                	<label class="col-lg-2 col-form-label">{{ __('messages.namekanji') }}</label>
                	<div class="col-lg-10">
                  		<input name="fullname" type="text" value="{{empty(old('fullname')) ? $teacher->name : old('fullname')}}" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" required="">
                	</div>
              	</div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.namekatakana') }}</label>
                    <div class="col-lg-10">
                        <input name="furigana" type="text" class="form-control{{ $errors->has('furigana') ? ' is-invalid' : '' }}" value="{{empty(old('furigana')) ? $teacher->furigana : old('furigana')}}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.nickname') }}</label>
                    <div class="col-lg-10">
                        <input name="nickname" type="text" class="form-control{{ $errors->has('nickname') ? ' is-invalid' : '' }}" value="{{empty(old('nickname')) ? $teacher->nickname : old('nickname')}}" required="">
                    </div>
                </div>
                <div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.username')}}</label>
	            	<div class="col-lg-10">
	              		<input name="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" value="{{empty(old('username')) ? $teacher->username : old('username')}}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.email') }}</label>
                    <div class="col-lg-10">
                        <input name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{empty(old('email')) ? $teacher->user->email : old('email')}}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.zoom-email') }}</label>
                    <div class="col-lg-10">
                        <input name="zoom_email" type="email" class="form-control{{ $errors->has('zoom_email') ? ' is-invalid' : '' }}" value="{{ old('zoom_email', $teacher->user->zoom_email) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.birthday') }}</label>
                    <div class="col-lg-10">
                        <input name="birthday" type="date" class="form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}" value="{{empty(old('birthday')) ? $teacher->birthday : old('birthday')}}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.address') }}</label>
                    <div class="col-lg-10">
                        <input name="birthplace" type="text" class="form-control{{ $errors->has('birthplace') ? ' is-invalid' : '' }}" value="{{empty(old('birthplace')) ? $teacher->birthplace : old('birthplace')}}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.calendar-color-coding') }}</label>
                    <div class="col-lg-10">
                        <div id="color_picker" data-default="{{ $teacher->color_coding ? $teacher->color_coding : $default_color }}"></div>
                        <input type="hidden" value="{{ $teacher->color_coding }}" name="color_coding">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.profile') }}</label>
                    <div class="col-lg-10">
                      <textarea name="profile" class="form-control{{ $errors->has('profile') ? ' is-invalid' : '' }}">{{empty(old('profile')) ? $teacher->profile : old('profile')}}</textarea>
                    </div>
                </div>
                @if (count($custom_fields) > 0)
			        @foreach ($custom_fields as $custom_field) 
                        @php 
                            $custom_value = '';
                            $value = $custom_field->custom_field_values->where('model_id', $teacher->id)->first(); 
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
                        <input name="edit" type="submit" value="{{ __('messages.edit') }}" class="form-control btn-success">
                    </div>
                </div>
	        </form>
        </div>
    </div>
@endsection
