@extends('layouts.app')
@section('title', ' - '. __('messages.payments'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mt-2" id="vue-app">
            <app-payment-list 
                :filter="{{ json_encode($filter) }}"
                :plans="{{ json_encode($plans) }}"
                :discounts="{{ json_encode($discounts) }}"
                :payment_methods="{{ json_encode($payment_methods) }}"
                :payment_categories="{{ json_encode($payment_categories) }}"
            ></app-payment-list>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/accounting/payments.js') }}"></script>
@endpush