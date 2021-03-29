@extends('layouts.application')
@section('content')
    <h1>{{ __('messages.application-form') }}</h1>
    <div class="steps-rows" style="text-align:center;"> 
        <a href="javascript:void(0)"><span class="step"></span><span class="steptext">{{__('messages.information')}}</span></a>
        @if(\App\Settings::get_value('application_docs'))
         <a href="javascript:void(0)"><span class="step"></span><span class="steptext">{{__('messages.documents')}}</span></a>
        @endif
        <a href="javascript:void(0)"><span class="step active"></span><span class="steptext">{{__('messages.complete')}}</span></a>
    </div>
   <div class="thank_you-sctn text-center">
    <div class="circle-check"><i class="fas fa-check-circle"></i></div>
    <h2 class="display-3">{{ __('messages.thank-you') }}</h2>
    <p class="lead">{{ __('messages.your-application-no-is') }} <strong>{{$application->application_no}}</strong> </p>
    @if (!\App\Settings::get_value('application_docs') || (!empty($application->docs) && count($application->docs) > 0)) 
    <p>{{ __('messages.final-thanks-message') }}</p>
    @else
    <p>{{__('messages.come-back-for-doc-upload')}}</p>
    @endif
    <!-- <p class="lead">
        <a class="btn btn-primary btn-sm" href="#" role="button">Continue to homepage</a>
    </p> -->
 
</div>

@endsection
