@extends('layouts.app')
@section('title', ' - '. __('messages.discounts'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mt-2" id="vue-app">
            <app-discounts
                :permissions="{{ json_encode($permissions) }}"
                :duration_enum="{{ json_encode($durationEnum) }}"
                :use_stripe_subscription="{{ $use_stripe_subscription }}"
            ></app-discounts>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/discount/index.js') }}"></script>
@endpush