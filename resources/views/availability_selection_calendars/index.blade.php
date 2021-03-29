@extends('layouts.app')
@section('title', ' - '. __('messages.availability-selection-calendars'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mt-2" id="vue-app">
            <app-availbility-selection-calendar-list :permissions="{{ $permissions }}"></app-availbility-selection-calendar-list>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/availability_selection_calendars/index.js') }}"></script>
@endpush