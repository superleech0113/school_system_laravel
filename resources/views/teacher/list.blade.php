@extends('layouts.app')
@section('title', ' - '. __('messages.teacherlist'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <h2>{{ __('messages.teacherlist') }}</h2>
            @if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}  
                </div><br/>
            @endif
            @include('partials.error')

            @include('teacher.table', [ 'teachers' => $teachers ])
            
            @if(!$archivedTeachers->isEmpty())
                <h2>{{ __('messages.archived-teachers') }}</h2>

                @include('teacher.table', [ 'teachers' => $archivedTeachers ])
            @endif
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal inmodal" id="DropEventModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('messages.archive-teacher') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="archive_teacher_form" method="POST" action="" >
                        @csrf
                        
                        <input type="hidden" name="teacher_id" value="">
                        
                        <div class="form-group row">
                            <label class="col-lg-12 col-form-label">{{ __('messages.select-teacher-who-will-takeover-future-classes-of') }} <span id="current_teacher_name"></span></label>
                            <div class="col-lg-12">
                                <select name="take_over_teacher_id" class="form-control" required="">
                                    <option value="">{{ __('messages.selectteacher') }}</option>
                                    @if(!$teachers->isEmpty())
                                        @foreach($teachers as $teacher)
                                            <option value="{{$teacher->id}}">{{$teacher->nickname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div id="schedule-date">
                            <div class="form-group row">
                                <label class="col-lg-12 col-form-label">{{ __('messages.takeover-classes-from-date') }}: </label>
                                <div class="col-lg-12">
                                    <input class="form-control" type="date" name="take_over_date" required >
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                        <input type="hidden" name="teacher_id" value="">
                        <button type="submit" class="btn btn-primary pull-left">{{ __('messages.archive') }}</button>
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal" aria-label="Close">{{ __('messages.cancel')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush


@push('scripts')
    <script>
        var today_date = "{{ $today_date }}";
    </script>
    <script src="{{ mix('js/page/teacher/list.js') }}"></script>
@endpush