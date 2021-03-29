@extends('layouts.app')
@section('title', ' - '. __('messages.application-settings'))

@section('content')
	<div class="row justify-content-center">
		<div class="col-12">
			<h1>{{ __('messages.application-settings') }}</h1>
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
			<form method="POST" action="{{ route('application-settings.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_series') }}</label>
                    <div class="col-lg-10">
                        <input name="application_series" type="number" min="0" value="{{ old('application_series', $application_series) }}" class="form-control{{ $errors->has('application_series') ? ' is-invalid' : '' }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_instructions_en') }}</label>
                    <div class="col-lg-10">
                        <textarea name="application_instructions_en" class="form-control{{ $errors->has('application_instructions_en') ? ' is-invalid' : '' }}" >{{ old('application_instructions_en', $application_instructions_en) }}"</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_instructions_ja') }}</label>
                    <div class="col-lg-10">
                        <textarea name="application_instructions_ja" class="form-control{{ $errors->has('application_instructions_ja') ? ' is-invalid' : '' }}" >{{ old('application_instructions_ja', $application_instructions_ja) }}"</textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_bottom_instructions_en') }}</label>
                    <div class="col-lg-10">
                        <textarea name="application_bottom_instructions_en" class="form-control{{ $errors->has('application_bottom_instructions_en') ? ' is-invalid' : '' }}" >{{ old('application_bottom_instructions_en', $application_bottom_instructions_en) }}"</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_bottom_instructions_ja') }}</label>
                    <div class="col-lg-10">
                        <textarea name="application_bottom_instructions_ja" class="form-control{{ $errors->has('application_bottom_instructions_ja') ? ' is-invalid' : '' }}" >{{ old('application_bottom_instructions_ja', $application_bottom_instructions_ja) }}"</textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_doc_instructions_en') }}</label>
                    <div class="col-lg-10">
                        <textarea name="application_doc_instructions_en" class="form-control{{ $errors->has('application_doc_instructions_en') ? ' is-invalid' : '' }}" >{{ old('application_doc_instructions_en',$application_doc_instructions_en) }}"</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_doc_instructions_ja') }}</label>
                    <div class="col-lg-10">
                        <textarea name="application_doc_instructions_ja" class="form-control{{ $errors->has('application_doc_instructions_ja') ? ' is-invalid' : '' }}" >{{ old('application_doc_instructions_ja', $application_doc_instructions_ja) }}"</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.application_docs') }}</label>
                    <div class="col-lg-10">
                        <input name="application_docs" type="checkbox" {{ old('application_docs', $application_docs) ? 'checked' : '' }}>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"></label>
                    <div class="col-lg-10">
                        <input name="edit" type="submit" value="{{ __('messages.edit') }}" class="btn btn-success form-control">
                    </div>
                </div>
            </form>

        </div>
	</div>
@endsection

@push('scripts')
@endpush
