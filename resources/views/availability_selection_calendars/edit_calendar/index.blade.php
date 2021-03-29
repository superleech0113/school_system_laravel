@extends('layouts.app')
@section('title', ' - '. __('messages.manage-timeslots'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mt-2" id="vue-app">
            <app-edit-calendar cal_id="{{ $availableSelectionCalendar->id }}"></app-edit-calendar>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/availability_selection_calendars/edit_calendar/index.js') }}"></script>
@endpush