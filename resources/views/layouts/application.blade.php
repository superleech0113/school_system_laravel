<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Settings::get_value('school_name') }}</title>

    <!-- Scripts -->
    <script>window.baseUrl = '{{ url('/') }}';</script>
    <script src="{{ mix('js/app.js') }}" defer="defer"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.4.0/dist/min/dropzone.min.js"></script>
    <script src="{{ route('assets.lang')  }}"></script>
    @stack('scripts')

    <!-- Styles -->
    <link rel="shortcut icon" href="{{ tenant_asset('favicon.ico') }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/space.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.4.0/dist/min/dropzone.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="top-navigation fixed-nav pace-done application_page">
    <div id="wrapper">
        <header>
            <nav class="navbar navbar-expand-lg fixed-top">
                @php
                    $footer_links = \App\FooterLinks::orderBy('display_order')->get();
                @endphp
                <a class="navbar-brand @if(\Storage::disk('public')->exists('logo.jpeg')) navbar-logo @endif"
                    href="javascript:void(0);">
                    @if(\Storage::disk('public')->exists('logo.jpeg'))
                        <img src="{{ tenant_asset('logo.jpeg') }}" alt="{{ __('messages.logo') }}" >
                    @else
                        {{ App\Settings::get_value('school_name') }}
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <i class="fa fa-reorder"></i>
                </button>
            </nav>
        </header>

        <div class="gray-bg application_form_sctn">
            <div class="wrapper wrapper-content animated fadeIn">
                @yield('content')
            </div>
        </div>
        <footer class="fixed_application-ftr">
         
            <div class="row m-0">
                <div class="col-md-4 pull-left">
                    <strong>Copyright</strong> uTeach &copy; 2018-<?php echo \Carbon\carbon::now(\App\Helpers\CommonHelper::getSchoolTimezone())->format('Y'); ?>
                </div>
                
                <div class="footer-links col-md-4">
                    @if(!$footer_links->isEmpty())
                        <ul>
                        @foreach($footer_links as $footer_link)
                            <li><a href="{!! \App\Helpers\CommonHelper::addhttp($footer_link->link) !!}" target="_blank">{{ \App::getLocale() == 'en' ? $footer_link->label_en : $footer_link->label_ja }}</a></li>
                        @endforeach
                        </ul>
                    @endif
                </div>

                <div class="col-md-4 pull-right">
                    
                    <div class="pull-right">
                    <strong>100ピー</strong>
                </div>
	        </div>
	        
            </div>
            <div class="clear"></div>
        </footer>
    </div>
</body>
</html>
