@extends('layouts.guest')

@section('title', __('messages.request-password-reset-link'))

@section('content')
    <div class="middle-box text-center loginscreen animated fadeIn">
        <div>
            <div>
                @if(\Storage::disk('public')->exists('logo.jpeg'))
                    <img src="{{ tenant_asset('logo.jpeg') }}" alt="{{ __('messages.logo') }}" class="logo">
                @else
                    <h1 class="logo-name">{{ __(\App\Settings::get_value('school_initial')) }}</h1>
                @endif
           </div>
            <h3>{{ __('messages.request-password-reset-link') }}</h3>
            @include('partials.success')
            <form class="mt-3" role="form" method="post" action="{{ route('reset-password-link') }}">
                @csrf

                <div class="form-group">
                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" placeholder="{{ __('messages.enter-username') }}" value="{{ old('username') }}" required autofocus>
                    @if ($errors->has('username'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{ __('messages.submit') }}</button>
                <div class="text-right">
                    <a href="{{ route('login') }}">{{ __('messages.back-to-login') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection

