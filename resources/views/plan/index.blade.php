@extends('layouts.app')
@section('title', ' - '. __('messages.plans'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mt-2" id="vue-app">
            <app-plans
                :use_stripe_subscription="{{ $use_stripe_subscription }}"
                :permissions="{{ json_encode($permissions) }}"
            ></app-plans>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/plan/index.js') }}"></script>
@endpush