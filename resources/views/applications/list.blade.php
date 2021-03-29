@extends('layouts.app')
@section('title', ' - '. __('messages.applicationlist'))

@section('content')
    <div class="row justify-content-center">
        <form action="" id="filter_form">
            <input type="hidden" name="sort_field" id="sort_field" value="" >
            <input type="hidden" name="sort_dir" id="sort_dir" value="" >
            <input type="hidden" name="is_student" id="is_student" value="" >
        </form>
        <form id="reconfirm_form" method="post">
            @csrf
        </form>
        <div class="col-12">
            @if(session()->get('success'))
                    <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br/>
            @endif
            @include('partials.error')
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <h1 class="pull-left">{{ __('messages.applicationlist') }}</h1>
                    <div class="pull-right">
                        <select name="is_student" id="application_filter" class="form-control mb-1">
                            <option value="all" {{ $filter['is_student']  == 'all' ? 'selected' : ''}} >{{ __('messages.all') }}</option>
                            <option value="true" {{ $filter['is_student']  == 'true' ? 'selected' : ''}}>{{ __('messages.converted_to_student') }}</option>
                            <option value="false" {{ $filter['is_student']  == 'false' ? 'selected' : ''}}>{{ __('messages.pending_applications') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-hover">
                <tbody>
                    @if(!$applications->isEmpty())
                        <tr>
                            <th data-collumn_name="application_no" class="collumn_sort">{{ __('messages.application_no') }}</th>
                            <th data-collumn_name="fullname" class="collumn_sort">{{ __('messages.name') }}</th>
                            <th data-collumn_name="toiawase_date" class="collumn_sort">{{ __('messages.firstcontactdate') }}</th>
                            <th data-collumn_name="birthday" class="collumn_sort">{{ __('messages.birthday') }}</th>
                            <th data-collumn_name="mobile_phone" class="collumn_sort">{{ __('messages.cellphone') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                        @foreach($applications as $application)
                            <tr>
                                <td><a href="{{ url('/applications/'.$application->id) }}" data-toggle="popover" data-placement="right" data-img="{{ $application->image ? $application->getImageUrl() : '' }}">{{$application->application_no}}</a></td>
                                <td><a href="{{ url('/applications/'.$application->id) }}" data-toggle="popover" data-placement="right" data-img="{{ $application->image ? $application->getImageUrl() : '' }}">{{$application->fullname}}</a></td>
                                <td>{{ $application->toiawase_date }}</td>
                                <td>{{ $application->birthday }}</td>
                                <td>{{ $application->mobile_phone }}</td>
                                <td>
                                    @if(empty($application->student_id))
                                        @can('convert-to-student')
                                        <a class="btn btn-success btn-sm mb-1"
                                            href="{{ route('applications.convert-to-student',['application_id' => $application->id]) }}">
                                            {{ __('messages.convert-to-student') }}
                                        </a>
                                        @endcan
                                        @can('application-edit')
                                            <a class="btn btn-warning btn-sm mb-1" href="{{ route('applications.edit', $application->id) }}">{{ __('messages.edit') }}</a>
                                        @endcan
                                    @endif
                                    @can('application-delete')
                                        <form class="delete mb-0" method="POST" action="{{ route('applications.destroy', $application->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm mb-1" type="submit">{{ __('messages.delete') }}</button>
                                        </form>    
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var _sort_field = "{{ @$filter['sort_field'] }}";
        var _sort_dir = "{{ @$filter['sort_dir'] }}";
    </script>
    <script src="{{ mix('js/page/application/list.js') }}"></script>
@endpush
