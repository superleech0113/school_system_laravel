@extends('layouts.app')
@section('title', ' - '. __('messages.class-usage'))

@section('content')
    <div class="col-lg-12 col-md-12">
        @include('student.class_usage_tab')
    </div>
@endsection
