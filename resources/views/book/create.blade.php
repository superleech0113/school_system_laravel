@extends('layouts.app')
@section('title', ' - '. __('messages.addbook'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12" id="vue-app">
            <app-add-book
                :book_levels="{{ json_encode($book_levels) }}"
                today="{{ $today }}"
                ></app-add-book>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset(mix('js/page/book/create.js')) }}"></script>
@endpush