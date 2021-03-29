@extends('layouts.app')
@section('title', ' - '. $teacher->name)

@section('content')
    <div class="row justify-content-center">
		<div class="col-lg-12">
            <h2>{{$teacher->name}}</h2>
            <div class="col-lg-12">
                <div class="row">
                    <label class="col-lg-4">{{ __('messages.name') }}</label>
                    <div class="col-lg-8">{{$teacher->name}}({{$teacher->furigana}})</div>
                </div>
                <div class="row">
                    <label class="col-lg-4">{{ __('messages.nickname') }}</label>
                    <div class="col-lg-8">{{$teacher->nickname}}</div>
                </div>
                <div class="row">
                    <label class="col-lg-4">{{ __('messages.hometown') }}</label>
                    <div class="col-lg-8">{{$teacher->birthplace}}</div>
                </div>
                <div class="row">
                    <label class="col-lg-4">{{ __('messages.birthday') }}</label>
                    <div class="col-lg-8">{{$teacher->birthday}}</div>
                </div>
                <div class="row">
                    <label class="col-lg-4">{{ __('messages.profile') }}</label>
                    <div class="col-lg-8">{{$teacher->profile}}</div>
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
                        @if(!empty($custom_value))
                        <div class="row">
                            <label class="col-lg-4">{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}</label>
                            <div class="col-lg-8"> 
                                @if($custom_field->field_type == 'link')
                                    <a href="{!! \App\Helpers\CommonHelper::addhttp($custom_value) !!}" target="_blank">
                                            {!! $custom_value !!}
                                    </a>
                                @elseif($custom_field->field_type == 'link-button')
                                    <a href="{!! \App\Helpers\CommonHelper::addhttp($custom_value) !!}" class="btn btn-primary" target="_blank">
                                        {!! $custom_value !!}
                                    </a>
                                @else
                                    {!! $custom_value !!}
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
                                
            </div>
        </div>
    </div>
@endsection
