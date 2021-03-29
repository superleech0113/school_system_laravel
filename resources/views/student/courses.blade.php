@extends('layouts.app')
@section('title', ' - '. __('messages.mycourses'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-6">
                    <h1>{{ __('messages.mycourses') }}</h1>
                </div>
            </div>
        </div>
        <div class="col-12">
            @if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br/>
            @endif
            @include('partials.error')
            <table class="table table-hover">
                <tbody>
                    @if(!$courseSettings->isEmpty())
                        <tr>
                            <th>{{ __('messages.course') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                        @foreach($courseSettings as $courseSetting)
                            <tr>
                                <td>{{ $courseSetting->course->title }}</td>
                                <td>
                                    @can('st-courses-details')
                                        <a class="btn btn-primary" href="{{ route('student.course_details', ['course_id' => $courseSetting->course->id]) }}">{{ __('messages.view-details') }}</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <p class="text-center">{{ __('messages.no-records-found') }}</p>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

