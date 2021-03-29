@extends('layouts.application')
@section('content')
	<div class="hdeader-lang">
	<h1>{{ __('messages.application-form') }}</h1>
	<select class="form-control" data-url="{{route('change-language')}}" id="changeLanguage">
		<option value="en" <?php if(app()->getLocale() == 'en') echo 'selected'; ?>>English</option>
		<option value="ja" <?php if(app()->getLocale() == 'ja') echo 'selected'; ?>>Japanese</option>
	</select>
	</div>
	<div class="steps-rows" style="text-align:center;"> 
		<a href="javascript:void(0)"><span class="step active"></span><span class="steptext">{{__('messages.information')}}</span></a>
		@if(\App\Settings::get_value('application_docs'))
		<a href="javascript:void(0)"><span class="step"></span><span class="steptext">{{__('messages.documents')}}</span></a>
		@endif
		<a href="javascript:void(0)"><span class="step"></span><span class="steptext">{{__('messages.complete')}}</span></a>
	</div>
	        
  <div class="row justify-content-center">
        <div class="col-12">
			<div class="col-lg-12">
				<div class="form-group">
					{{ \App\Settings::get_value(\App::getLocale() == 'en' ? 'application_instructions_en' : 'application_instructions_ja') }}	
				</div>
			</div>
				
        	@include('partials.success')
			@include('partials.error')
			<form method="POST" action="{{ route('application.save') }}" id="application-form" enctype="multipart/form-data">
	        	@csrf
				@foreach($fields as $field)
					@if($field->is_visible)
						@if($field->is_custom)
							@include('applications.fields.custom_field')
						@else
							@include('applications.fields.'.$field->field_name)
						@endif
					@endif
				@endforeach
				<div class="clearfix"></div>
				<div class="col-lg-12">
					<div class="form-group">
						{{ \App\Settings::get_value(\App::getLocale() == 'en' ? 'application_bottom_instructions_en' : 'application_bottom_instructions_ja') }}	
					</div>
				</div>
				
				<div class="col-lg-12">
	            	<div class="form-group">
						<label class="col-form-label"><input type="checkbox" name="agreement" id="terms"><span>
						{{__('messages.terms-message') }}	
						</span></label>
	         	  	</div>
	          	</div>
				<div class="col-lg-12">
	            	<div class="form-group">
						<span class="pull-right">
					@if(\App\Settings::get_value('application_docs'))
		     	  		<input name="add" type="submit" value="{{ __('messages.save_next') }}" class="btn btn-success">
					@endif
						   <input name="exit" type="submit" value="{{ __('messages.save_exit') }}" class="btn btn-success">
						</span>
					</div>
				  </div>
				  <div class="clearfix"></div>
	        </form>
      	</div>
    </div>
@endsection

@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="//gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
    window.levelsPlaceholder = "{{ __('messages.please-select-level-s') }}";
	window.addEventListener('DOMContentLoaded', function() {
        $('#levels').select2({
            width: '100%',
            placeholder: levelsPlaceholder
		});
    });
</script>

<script src="{{ mix('js/page/application/create.js') }}"></script>
@endpush
