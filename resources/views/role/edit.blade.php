@extends('layouts.app')
@section('title', ' - '. __('messages.editrole'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
        	@include('partials.error')
            @include('partials.success')
	        <form method="POST" action="{{ route('roles.update',$role->id) }}">
	            @method('PATCH')
	        	@csrf
	          	<h1>{{ __('messages.editrole') }} {{$role->name}}</h1>
	          	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.name') }}</label>
	            	<div class="col-lg-10">
	              		<input name="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ empty(old('name'))?$role->name:old('name') }}" required="">
	            	</div>
				 </div>
				 <div class="form-group row">
					<label class="col-lg-2 col-form-label">{{ __('messages.defaultlanguage') }}</label>
					<div class="col-lg-10">
						<select name="default_lang" class="form-control">
							<option value="">Select Language</option>
							<option value="en" <?php if($role->default_lang == 'en') echo 'selected'; ?>>English</option>
							<option value="ja" <?php if($role->default_lang == 'ja') echo 'selected'; ?>>Japanese</option>
						</select>
					</div>
			  	</div>
	         	<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.permissions') }}</label>
	            	<div class="col-lg-10">

						@foreach ($categories as $category)
							<button class="btn btn-block btn-light" id="btn_{{$category->name}}" type="button" data-toggle="collapse" data-target="#permission_container_{{$category->name}}" aria-expanded="false" aria-controls="permission_container">
								{{ __('messages.'.$category->name) }}
								
							</button>
						
							<div class="collapse row" style="margin-left:0px; padding:10px;" id="permission_container_{{$category->name}}">
								@foreach ($category->permissions as $permission)
								<div class="col-md-3 mb-2">
									<div class="checkbox m-r-xs">
										<input type="checkbox" data-toggle="toggle" name="permissions[]" value="{{$permission->id}}" id="checkbox{{$permission->id}}" {{ in_array($permission->id,$roleper) ? 'checked' : '' }}>
										<label for="checkbox{{$permission->id}}" data-toggle="tooltip" title="{{ \App::getLocale()=='en' ? $permission->tooltip_en : $permission->tooltip_ja }}">
											{{ __('messages.'.$permission->name) }}
										</label>
									</div>
								</div>
								@endforeach
							</div>
						@endforeach
			

	            	</div>
	         	</div>
				<div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.can-login') }}</label>
                    <div class="col-lg-10">
						<input type="checkbox" data-toggle="toggle" data-onstyle="info" name="can_login" @if($role->can_login === 1) checked @endif>
                    </div>
				</div>
				<div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.can-add-user') }}</label>
                    <div class="col-lg-10">
                        <input name="can_add_user" type="checkbox" data-toggle="toggle" @if($role->can_add_user === 1) checked @endif>
                    </div>
				</div>
				
				<div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.login-redirect-path') }}</label>
                    <div class="col-lg-10">
                        <input name="login_redirect_path" type="text" class="form-control{{ $errors->has('login_redirect_path') ? ' is-invalid' : '' }}" value="{{ $role->login_redirect_path }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.is-student') }}</label>
                    <div class="col-lg-10">
						<input type="checkbox" data-toggle="toggle" data-onstyle="info" name="is_student" @if($role->is_student === 1) checked @endif>
                    </div>
				</div>
				<div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.sendlogindetails') }}</label>
                    <div class="col-lg-10">
						
						<input type="checkbox" data-toggle="toggle" data-onstyle="info"  name="send_login_details" @if($role->send_login_details === 1) checked @endif>
                    </div>
                </div>
	          	<div class="form-group row">
		            <label class="col-lg-2 col-form-label"></label>
		            <div class="col-lg-10">
						<input name="submit" type="submit" value="{{ __('messages.editrole') }}" class="form-control btn-success">
		            </div>
		        </div>
	        </form>
      	</div>
    </div>
@endsection

@push('scripts')
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<script>
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip({
				classes: {
					"ui-tooltip": "tooltip fade bs-tooltip-top show"
				}
			});
		});
	</script>
@endpush

@push('styles')
	<link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush