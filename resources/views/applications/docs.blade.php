@extends('layouts.application')
@section('content')
    <h1>{{ __('messages.application-form') }}</h1>
    <div class="steps-rows" style="text-align:center;"> 
        <a href="javascript:void(0)"><span class="step"></span><span class="steptext">{{__('messages.information')}}</span></a>
        <a href="javascript:void(0)"><span class="step active"></span><span class="steptext">{{__('messages.documents')}}</span></a>
        <a href="javascript:void(0)"><span class="step"></span><span class="steptext">{{__('messages.complete')}}</span></a>
    </div>
	<div class="row justify-content-center">
        <div class="col-12">
            <div class="col-lg-12">
				<div class="form-group">
					{{ \App\Settings::get_value(\App::getLocale() == 'en' ? 'application_doc_instructions_en' : 'application_doc_instructions_ja') }}	
				</div>
			</div>
			
        	@include('partials.success')
            @include('partials.error')
            <div class="dropzone application_files" id="application_files"></div>
            <div class="form-group row mt-2">
                <div class="col-lg-12">
                    <a href="{{route('application.complete',['application_no' => base64_encode($application->application_no)])}}" class="btn btn-success pull-right">{{ __('messages.complete') }}</a>
                </div>
            </div>
	    </div>
    </div>
@endsection

@push('scripts')
<script>
    window.uploadApplicationFileUrl = "{{ route('applicationdocs.upload',['application_id' => $application->id]) }}";
    window.deleteApplicationFileUrl = "{{ route('applicationdocs.delete',['']) }}";
</script>

<script src="{{ mix('js/page/application/create.js') }}"></script>
@endpush
