@extends('layouts.app')
@section('title', ' - '. __('messages.checkin'))

@section('content')
    <h1>{{ __('messages.checkin')}}</h1>
    <div class="container text-center">
        @include('partials.error')

    </div>
@endsection
