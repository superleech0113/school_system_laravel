@extends('layouts.app')
@section('title', ' - '. __('messages.pwa-settings'))
@push('styles')
    <style>
        .pwa-title-box {
            padding: 25px 15px;
        }
        .pwa-title {
            color: #000000;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 30px;
            flex: 1; 
            align-self: center;
            margin: 0px;
        }
        .pwa-title strong {
            color: #1ab394;
        }
        .pwa-title span {
            font-size: 12px;
            color: #e3342f;
        }
        .pwa-title-tools {
            align-self: center;
        }
        .pwa-label {
            color: #2d995b;
        }
        .pwa-label span {
            float: right;
            color: #e3342f;
        }

        @media (max-width: 575.98px) {}
        @media (min-width: 576px) and (max-width: 767.98px) {}
        @media (min-width: 768px) and (max-width: 991.98px) {
            .pwa-label span {
                float: none;
            }
        }
        @media (min-width: 992px) and (max-width: 1199.98px) {}
        @media (min-width: 1200px) {}
    </style>
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br/>
            @endif
            @if(!empty($errors->all()))
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">
                    {{$error }}
                </div>
                @endforeach
            @endif
            <h1>{{ __('messages.pwa-settings') }}</h1>
            {{-- PWA actions --}}
            @if(!$pwa_data)
            <form method="POST" action="{{ route('tenant.pwa.store') }}">
                @csrf
                <button class="btn btn-success" type="submit">{{ __('messages.make-pwa') }}</button>
            </form>
            @elseif($pwa_data && $pwa_status == 0)
            <form method="POST" action="{{ route('tenant.pwa.activate') }}" class="d-inline-block">
                @csrf
                <button class="btn btn-success mb-3 mt-3" type="submit">{{ __('messages.activate-pwa') }}</button>
            </form>
            <form method="POST" action="{{ route('tenant.pwa.delete') }}" class="d-inline-block float-right">
                @csrf
                @method('delete')
                <button class="btn btn-danger mb-3 mt-3" type="submit">{{ __('messages.delete-pwa') }}</button>
            </form>
            @elseif($pwa_data && $pwa_status == 1)
            <form method="POST" action="{{ route('tenant.pwa.deactivate') }}" class="d-inline-block">
                @csrf
                <button class="btn btn-warning mb-3 mt-3" type="submit">{{ __('messages.deactivate-pwa') }}</button>
            </form>
            <form method="POST" action="{{ route('tenant.pwa.delete') }}" class="d-inline-block float-right">
                @csrf
                @method('delete')
                <button class="btn btn-danger mb-3 mt-3" type="submit">{{ __('messages.delete-pwa') }}</button>
            </form>
            {{-- PWA app information --}}
            <form method="POST" action="{{ route('tenant.pwa.update') }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label pwa-label">{{ __('messages.app-name') }} <span>*</span></label>
                    <div class="col-lg-10">
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $pwa_data['manifest']['name'] ?? '' }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label pwa-label">{{ __('messages.app-short-name') }} <span>*</span></label>
                    <div class="col-lg-10">
                        <input type="text" name="short_name" class="form-control {{ $errors->has('short_name') ? ' is-invalid' : '' }}" value="{{ $pwa_data['manifest']['short_name'] ?? '' }}" required>
                        @if ($errors->has('short_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('short_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label pwa-label">{{ __('messages.start-url') }} <span>*</span></label>
                    <div class="col-lg-10">
                        <input type="text" name="start_url" class="form-control {{ $errors->has('start_url') ? ' is-invalid' : '' }}" value="{{ $pwa_data['manifest']['start_url'] ?? '' }}" required>
                        @if ($errors->has('start_url'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('start_url') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label pwa-label">{{ __('messages.background-color') }}</label>
                    <div class="col-lg-10">
                        <input type="color" name="background_color" class="form-control" value="{{ $pwa_data['manifest']['background_color'] ?? '' }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label pwa-label">{{ __('messages.theme-color') }}</label>
                    <div class="col-lg-10">
                        <input type="color" name="theme_color" class="form-control" value="{{ $pwa_data['manifest']['theme_color'] ?? '' }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label pwa-label">{{ __('messages.display-type') }} <span>*</span></label>
                    <div class="col-lg-10">
                        <select name="display" class="form-control" required>
                            <option value="standalone" {{ ($pwa_data['manifest']['display'] == 'standalone') ? 'selected=selected': false }}>Standalone</option>
                            <option value="fullscreen" {{ ($pwa_data['manifest']['display'] == 'fullscreen') ? 'selected=selected': false }} disabled="disabled">Fullscreen</option>
                            <option value="minimal-ui" {{ ($pwa_data['manifest']['display'] == 'minimal-ui') ? 'selected=selected': false }} disabled="disabled">Minimal UI</option>
                            <option value="browser" {{ ($pwa_data['manifest']['display'] == 'browser') ? 'selected=selected': false }} disabled="disabled">Browser</option>
                        </select>
                        <p class="mt-3">{{ __('messages.for-more-details') }} <a href="https://web.dev/add-manifest/#display"> {{ __('messages.click-here') }}</a></p>
                    </div>
                </div>

                {{-- PWA icons --}}
                <div class="ibox collapsed">
                    <div class="ibox-title d-flex pwa-title-box">
                        <h3 class="pwa-title"><strong>{{ __('messages.icons') }}</strong> <span>({{ __('messages.all-icons-must-be-png-format') }})</span></h3>
                        <div class="ibox-tools pwa-title-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="ibox-content" style="display: none;">
                        @foreach($pwa_data['manifest']['icons'] as $key => $icon)

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label pwa-label">{{ $key }} <span>*</span></label>
                            <div class="col-10">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="{{ $key }}" name="icons[{{ $key }}]" data-default_placeholder="{{ __('messages.change-icon') }}" accept="image/x-png">
                                        <label class="custom-file-label" for="{{ $key }}" aria-describedby="{{ $key }}"></label>
                                    </div>
                                </div>
                                @if(\Storage::disk('public')->exists('/pwa/images/icons/icon-' . $key . '.png'))
                                    <img src="{{ $icon['path'] }}" alt="{{ $key }}" style="max-width:100px;">
                                @endif
                            </div>
                        </div>

                        @endforeach
                    </div>
                </div>
                {{-- PWA splashes --}}
                <div class="ibox collapsed">
                    <div class="ibox-title d-flex pwa-title-box">
                        <h3 class="pwa-title"><strong>{{ __('messages.splashes') }}</strong><span>({{ __('messages.all-splashes-must-be-png-format') }})</span></h3>
                        <div class="ibox-tools pwa-title-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="ibox-content" style="display: none;">
                        @foreach($pwa_data['manifest']['splash'] as $splash => $path)
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label pwa-label">{{ $splash }} <span>*</span></label>
                            <div class="col-10">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="{{ $splash }}" name="splashes[{{$splash }}]" data-default_placeholder="{{ __('messages.change-splash') }}" accept="image/x-png">
                                        <label class="custom-file-label" for="{{ $splash }}" aria-describedby="{{ $splash }}"></label>
                                    </div>
                                </div>
                                @if(\Storage::disk('public')->exists('/pwa/images/icons/splash-' . $splash . '.png'))
                                    <img src="{{ $path }}" alt="{{ $splash }}" style="max-width:100px;">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-success" type="submit">{{ __('messages.update') }}</button>
                </div>
            </form>
            @endif
        </div>
    </div>
@endsection
