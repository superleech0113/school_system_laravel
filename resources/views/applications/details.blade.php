@extends('layouts.app')
@section('title', ' - '. $application->fullName)

@section('content')
        <div class="row">
            <div class="col-lg-12">
                @if(session()->get('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div><br/>
                @endif
                @if(session()->get('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div><br/>
                @endif
                <div class="border-bottom">
                    <div class="pull-left" style="max-width:50%">
                        <div class="align-middle d-inline-block">
                            <h2>{{ $application->fullName }}</h2>
                        </div>
                    </div>
                    <div class="pull-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                @if(empty($application->student_id))
                                    @can('convert-to-student')
                                    <a class="dropdown-item"
                                        href="{{ route('applications.convert-to-student',['application_id' => $application->id]) }}">
                                        {{ __('messages.convert-to-student') }}
                                    </a>
                                    @endcan
                                    @can('application-edit')
                                        <a class="dropdown-item" href="{{ route('applications.edit', $application->id) }}">{{ __('messages.edit') }}</a>
                                    @endcan
                                @endif
                                @can('application-delete')
                                    <form class="mb-0" method="POST" action="{{ route('applications.destroy', $application->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <a class="dropdown-item delete-application" href="javascript:void(0);">{{ __('messages.delete')}}</a>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 sticky_tabs_container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link {{(!isset($nav)) || $nav == 'home' ? 'active' : ''}}" data-toggle="tab" href="#home">{{ __('messages.personalinformation')}}</a></li>
                    @if(!$application->docs->isEmpty())
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'docs') ? 'active' : ''}}" data-toggle="tab" href="#docs">{{ __('messages.docs')}}</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-lg-12 tab-content-txt">
                <div class="tab-content">
                    <div id="home" class="tab-pane fade {{ (!isset($nav)) || $nav == 'home' ? 'active show' : ''}}">
                        <h3>{{ __('messages.personalinformation')}}</h3>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    @if($application->image)
                                    <tr>
                                        <td>{{ __('messages.profile-picture')}}:</td>
                                        <td>
                                            <img src="{{ $application->getImageUrl() }}" style="max-width:300px;" class="img-responsive">
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>{{ __('messages.application_no')}}:</td>
                                        <td>{{$application->application_no}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('messages.lastnameromaji')}}:</td>
                                        <td>{{$application->lastname}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('messages.firstnameromaji')}}:</td>
                                        <td>{{$application->firstname}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('messages.lastnamekanji')}}:</td>
                                        <td>{{$application->lastname_kanji}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('messages.firstnamekanji')}}:</td>
                                        <td>{{$application->firstname_kanji}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('messages.lastnamekatakana')}}:</td>
                                        <td>{{$application->lastname_furigana}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('messages.firstnamekatakana')}}:</td>
                                        <td>{{$application->firstname_furigana}}</td>
                                    </tr>
                                    @if(!empty($application->join_date))
                                    <tr>
                                        <td>{{ __('messages.joindate')}}:</td>
                                        <td>{{$application->join_date}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->birthday))
                                    <tr>
                                        <td>{{ __('messages.birthday')}}:</td>
                                        <td>{{$application->birthday}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->address))
                                    <tr>
                                        <td>{{ __('messages.address')}}:</td>
                                        <td>{{$application->address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->home_phone))
                                    <tr>
                                        <td>{{ __('messages.homephone')}}:</td>
                                        <td>{{$application->home_phone}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->mobile_phone))
                                    <tr>
                                        <td>{{ __('messages.cellphone')}}:</td>
                                        <td>{{$application->mobile_phone}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->email))
                                    <tr>
                                        <td>{{ __('messages.email')}}:</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn" data-toggle="modal" data-target="#mail">{{$application->email}}</button>
                                        </td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->levels))
                                    <tr>
                                        <td>{{ __('messages.levels') }}:</td>
                                        <td>{{ implode(", ",explode(",",$application->levels)) }}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->toiawase_referral))
                                    <tr>
                                        <td>{{ __('messages.referrer')}}:</td>
                                        <td>{{ $application->toiawase_referral }}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->toiawase_houhou))
                                    <tr>
                                        <td>{{ __('messages.firstcontacttype')}}:</td>
                                        <td>
                                            @php
                                                switch ($application->toiawase_houhou) {
                                                    case 'Eメール':
                                                        $contact_type = __('messages.email');
                                                        break;

                                                    case '電話':
                                                        $contact_type = __('messages.telephone');
                                                        break;

                                                    case '直接':
                                                        $contact_type = __('messages.direct');
                                                        break;

                                                    case 'LINE':
                                                        $contact_type = __('messages.line');
                                                        break;

                                                    default:
                                                        $contact_type = "";
                                                        break;
                                                }
                                            @endphp
                                            {{ $contact_type }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->toiawase_date))
                                    <tr>
                                        <td>{{ __('messages.firstcontactdate')}}:</td>
                                        <td>{{ $application->toiawase_date}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->toiawase_memo))
                                    <tr>
                                        <td>{{ __('messages.memo')}}:</td>
                                        <td>{!! nl2br($application->toiawase_memo) !!}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->office_name))
                                    <tr>
                                        <td>{{ __('messages.office-name')}}:</td>
                                        <td>{{$application->office_name}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->office_address))
                                    <tr>
                                        <td>{{ __('messages.office-address')}}:</td>
                                        <td>{{$application->office_address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->office_phone))
                                    <tr>
                                        <td>{{ __('messages.office-phone')}}:</td>
                                        <td>{{$application->office_phone}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->school_name))
                                    <tr>
                                        <td>{{ __('messages.school-name')}}:</td>
                                        <td>{{$application->school_name}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->school_address))
                                    <tr>
                                        <td>{{ __('messages.school-address')}}:</td>
                                        <td>{{$application->school_address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($application->school_phone))
                                    <tr>
                                        <td>{{ __('messages.school-phone')}}:</td>
                                        <td>{{$application->school_phone}}</td>
                                    </tr>
                                    @endif
                                    @if (count($custom_fields) > 0)
			                            @foreach ($custom_fields as $custom_field) 
                                            @php 
                                                $custom_value = '';
                                                $value = $custom_field->custom_field_values->where('model_id', $application->id)->first(); 
                                                if (!empty($value)) {
                                                    $custom_value = $value->field_value;
                                                }
                                            @endphp
                                            @if(!empty($custom_value))
                                            <tr>
                                                <td>{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}:</td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(!$application->docs->isEmpty())
                    <div id="docs" class="tab-pane fade {{(isset($nav) && $nav == 'docs') ? 'active show' : ''}}">
                        <h3>{{ __('messages.docs')}}</h3>
                        <div class="row">
                            <div class="col-md-12">
                            {!! $application->the_docs_url() !!}
                            </div>
                        </div>
                    </div>
                    @endif
                   
                </div>

            </div>
        </div>
        <div id="mail" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4>{{ __('messages.sendmail')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('application.mail.send', $application->id) }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-lg-2">{{ __('messages.subject')}}</label>
                            <div class="col-lg-10">
                                <input type="subject" class="form-control" name="subject" required="">
                                <input type="hidden" value="{{ $application->email }}" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2">{{ __('messages.message')}}</label>
                            <div class="col-lg-10">
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2"></label>
                            <div class="col-lg-10">
                                <input type="submit" name="submit" value="Submit" class="btn btn-info form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.close')}}</button>
                </div>
            </div>
        </div>
    </div>

        @include('course.unit.lesson.file-name')
@endsection
@push('scripts')
<script src="{{ mix('js/page/filename.js') }}"></script>
@endpush
