@extends('layouts.app')
@section('title', ' - '. __('messages.payments'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <h1>{{  __('messages.payments') }}</h1>
        </div>
        <div id="vue-app" class="col-12">
            <app-monthly-payments
                :records="{{  json_encode($records) }}"
                :plans="[]"
                :payment_methods="[]"
                :payment_categories="[]"
                from_page="student_facing"
            ></app-monthly-payments>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/student/payments.js') }}"></script>
@endpush