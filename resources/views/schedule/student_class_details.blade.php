@extends('layouts.app')
@section('title', ' - '. __('messages.coursedetails'))

@section('content')
    @include('partials.success')
    @include('partials.error')
<?php
    $reserved = 0;
    $waitlist = 0;
?>
@foreach ($users as $user)
    @if(Auth::user()->id == $user->user_id)
        <?php
            $reserved = 1;
            $waitlist = $user->waitlist;
            break;
        ?>
    @endif
@endforeach


    <div class="row justify-content-center">
        <div class="col-12 sticky_tabs_container">
            @php
                $nav = Request::query('nav');
                if(!$nav)
                {
                    $nav = "course";
                }
            @endphp
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'course') ? 'active' : ''}}" data-toggle="tab" href="#course">{{ __('messages.coursedetails')}}</a></li>
                @if ($reserved && !$waitlist)
                <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'schedule') ? 'active' : ''}}" data-toggle="tab" href="#schedule">{{ __('messages.scheduledetails')}}</a></li>
                @endif
            </ul>
        </div>
        <div class="col-lg-12">
            <div class="tab-content">
                <div id="course" class="tab-pane fade {{(isset($nav) && $nav == 'course') ? 'active show' : ''}}">
                    @include('student.course_details_tab')
                </div>
                @if ($reserved && !$waitlist)
                <div id="schedule" class="tab-pane fade {{(isset($nav) && $nav == 'schedule') ? 'active show' : ''}}">
                    <div class="card card-body">
                        @include('schedule.details.tabs.comments')
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @include('course.unit.lesson.file-name')
@endsection

@push('scripts')
<script src="{{ mix('js/page/filename.js') }}"></script>
<script src="{{ mix('js/page/schedule/details/tabs/comments.js') }}"></script>
@endpush
