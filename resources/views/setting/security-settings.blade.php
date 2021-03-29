@extends('layouts.app')
@section('title', ' - '. __('messages.security-settings'))

@section('content')
	<div class="row justify-content-center">
		<div class="col-12">
			<h1>{{ __('messages.security-settings') }}</h1>
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
            @include('partials.error')
			<form method="POST" action="{{ route('security-settings.update') }}" enctype="multipart/form-data">
                @csrf
				<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.whitelisted_ip') }}</label>
	            	<div class="col-lg-10">
	              		<input name="whitelist_ips" type="text" class="form-control{{ $errors->has('whitelist_ips') ? ' is-invalid' : '' }}" value="{{empty(old('whitelist_ips')) ? $whitelist_ips : old('whitelist_ips')}}">
	            	</div>
	         	</div>
              
				<div class="form-group row">
	            	<label class="col-lg-2 col-form-label">{{ __('messages.restrict-login-to-IP') }}</label>
	            	<div class="col-lg-10">
						@foreach ($roles as $role)
	                    <div class="i-checks">
	                        <label>
	                            <input name="ip_security_role[]" type="checkbox" value="{{ $role->id }}"  {{ in_array($role->id, $ip_security_role)  ? 'checked' : '' }} >
	                            <i></i> {{ ucfirst($role->name) }}
	                            </label>
	                    </div>
                    	@endforeach
	            	</div>
                 </div>
                
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label"></label>
                    <div class="col-lg-3">
                        <input name="edit" type="submit" value="{{ __('messages.edit') }}" class="btn btn-success form-control">
                    </div>
                </div>
            </form>
		</div>
	</div>
@endsection
