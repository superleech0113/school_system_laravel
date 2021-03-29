@extends('layouts.app')
@section('title', ' - '. __('messages.addfooterlink'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @include('partials.success')
            @include('partials.error')
	        <form method="POST" action="{{ route('footer-link.store') }}">
	        	@csrf
	          	<h1>{{ __('messages.addfooterlink') }}</h1>
	          	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.fieldlabel_en') }}</label>
	            	<div class="col-lg-10">
	              		<input name="label_en" type="text" class="form-control{{ $errors->has('label_en') ? ' is-invalid' : '' }}" value="{{ old('label_en') }}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.fieldlabel_ja') }}</label>
	            	<div class="col-lg-10">
	              		<input name="label_ja" type="text" class="form-control{{ $errors->has('label_ja') ? ' is-invalid' : '' }}" value="{{ old('label_ja') }}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.link') }}</label>
	            	<div class="col-lg-10">
	              		<input name="link" type="text" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" value="{{ old('link') }}" required="">
	            	</div>
	         	</div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.display_order') }}</label>
                    <div class="col-lg-10">
                        <input type="number" min="0" name="display_order" class="form-control{{ $errors->has('display_order') ? ' is-invalid' : '' }}" value="{{ old('display_order') }}" required>
                    </div>
                </div>
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
