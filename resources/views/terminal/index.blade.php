@extends('layouts.app')
@section('title', ' - '. __('messages.terminal'))

@push('styles')
    <style>
        .btn-terminal {
            min-height: 250px;
            /* min-width: 225px; */
            display: block;
            width: 95%;
            margin: 5px;
            font-size: 20px;
            vertical-align: middle;
        }

        .btn-terminal.selected {
            border: 3px solid #f3f3f4;
            outline: 3px solid #045f4c;
            background-color: #045f4c;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid text-center">
        @include('partials.success')
        @include('partials.error')
        <div class="row no-gutters d-flex justify-content-center">
            @if(\App\Settings::get_value('terminal_checkin'))
                <div class="col-sm-6 col-md-6 col-lg-3 check">
                    <button id="checkin-btn" class="btn btn-primary btn-terminal">
                        <p style="font-size:13px;" class="btn-active-text">{{ __('messages.scan-rfid-to')}}</p>
                        {{ __('messages.terminal-check-in')}}
                        <p id="checkin_token_text" class="checkoutEditable" style="font-size:13px; "></p>
                        <div id="checkin_spinner" class="fa fa-spinner fa-spin spinner" style="display:none;"></div>
                    </button>
                </div>
            @endif
            @if(\App\Settings::get_value('terminal_reservation'))
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <button class="btn btn-primary btn-terminal"
                            onclick="window.location.href='{{ route('terminal.make_reservation') }}'">{{ __('messages.make-reservation')}}</button>
                </div>
            @endif
            @if(\App\Settings::get_value('terminal_checkout_book'))
                <div class="col-sm-6 col-md-6 col-lg-3 check">
                    <button id="checkout-book-btn" class="btn btn-primary btn-terminal">
                        <p style="font-size:13px;" class="btn-active-text">{{ __('messages.scan-rfid-to')}}</p>
                        {{ __('messages.checkout-book')}}
                        <p id="checkout_book_text" class="checkoutEditable" style="font-size:13px;"></p>
                        <div id="checkout_spinner" class="fa fa-spinner fa-spin spinner" style="display:none;"></div>
                    </button>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 check" style="display: none">
                    <button id="barcode-btn" class="btn btn-primary btn-terminal">
                        <p style="font-size:13px;" class="btn-active-text">{{__('messages.scan-book')}}</p>
                        {{ __('messages.checkout-book')}}
                        <p id="checkout_book_barcode" class="checkoutEditable" style="font-size:13px;"></p>
                        <div id="checkout_spinner" class="fa fa-spinner fa-spin spinner" style="display:none;"></div>
                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let generic_error_message = "{{ __('messages.something-went-wrong') }}";
            $('.btn-terminal').on('click', function (e) {

                $('.btn-terminal').removeClass('selected');
                $('.btn-active-text').hide();
                let active_btn = $(e.target);

                active_btn.addClass('selected');
                active_btn.find('.btn-active-text').show();

                let checkoutEditable = active_btn.find('.checkoutEditable');
                checkoutEditable.attr('contentEditable', true).focus();

                checkoutEditable.off('keypress').on('keypress', function (e) {
                    let selfEditable = $(e.target);
                    if (e.keyCode === 13) {
                        active_btn.find('.spinner').show();
                        let val = selfEditable.text();
                        switch (selfEditable.attr('id')) {
                            case 'checkin_token_text' :
                                checkSubmit({
                                    rfid_token: val
                                }, "{{ route('terminal.checkin_submit') }}", selfEditable, selfEditable, active_btn);
                                break;
                            case 'checkout_book_text' :
                                checkSubmit({
                                    rfid_token: val,
                                    barcode: null,
                                }, "{{ route('terminal.checkout_book_submit') }}", "{{ __('messages.successfully-checkedout') }}", selfEditable, active_btn);
                                break;
                        }
                    }
                })
            });
            $('#checkin-btn').click();
        });

        function checkSubmit(params, url, title = false, elem = false, active_btn = false) {
            $.ajax({
                url: url,
                type: 'POST',
                data: params,
                success: function (res) {
                    if (res.status && res.barcode) {
                        elem = $('#checkout_book_text');
                        $('#checkout_book_barcode').closest('.check').hide();
                        elem.closest('.check').show();
                        elem.closest('button').click();
                        Swal.fire({
                            title: title,
                            html: res.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            timer: 2000,
                            confirmButtonText: trans('messages.ok'),
                        });
                    } else if (res.barcode === 0) {
                        if(res.errorBarcodeMessage){
                            Swal.fire({
                                text: res.errorBarcodeMessage || generic_error_message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                timer: 2000,
                                confirmButtonText: trans('messages.ok'),
                            });
                        }
                        elem = $('#checkout_book_barcode');
                        $('#checkout_book_text').closest('.check').hide();
                        elem.closest('.check').show();
                        elem.closest('button').click();

                        elem.on('keypress', function (e) {
                            if (e.keyCode === 13) {
                                checkSubmit({
                                    rfid_token: res.rfid_token,
                                    barcode: elem.text(),
                                }, url, title, false, active_btn)
                            }
                        })
                    } else if (res.status === 0) {
                        Swal.fire({
                            text: res.message || generic_error_message,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            timer: 2000,
                            confirmButtonText: trans('messages.ok'),
                        });
                    } else if (res.status === 1) {
                        Swal.fire({
                            title: res.title,
                            html: res.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            timer: 2000,
                            confirmButtonText: trans('messages.ok'),
                        });
                    }
                    elem.text('').focus();
                    $('.spinner').hide();
                }
            })
        }
    </script>
@endpush

@push('styles')
    <style>
        .checkoutEditable:focus {
            border: none !important;
            outline: none;
        }
    </style>
@endpush
