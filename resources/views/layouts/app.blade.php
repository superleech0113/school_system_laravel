<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ App\Settings::get_value('school_name') }}@yield('title')</title>
    <!-- Scripts -->
    <script>window.baseUrl = '{{ url('/') }}';</script>
    <script src="{{ mix('js/app.js') }}" defer="defer"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script defer type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.4.0/dist/min/dropzone.min.js"></script>
    <script src="{{ route('assets.lang')  }}"></script>
    @stack('scripts')
    <script>
        window.search = '{{ request()->search }}';
        window.is_search = "{{ request()->route()->getName() == 'student.search' ? true : false }}";
    </script>

    <!-- Styles -->
    <link rel="shortcut icon" href="{{ tenant_asset('favicon.ico') }}">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.4.0/dist/min/dropzone.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @stack('styles')

    @include('pwa.meta')
</head>
<body class="top-navigation fixed-nav pace-done">
    <div id="wrapper">
        <header>
            <nav class="navbar navbar-expand-lg fixed-top">
                @php
                    $footer_links = \App\FooterLinks::orderBy('display_order')->get();
                    $brand_url = "";
                    $user = Auth::user();
                    $role =  $user ? $user->get_role() : null;
                    $currentRoute = Route::currentRouteName();
                    if($currentRoute == "terminal.index")
                    {
                        $brand_url = route('terminal.index');
                    }
                    else
                    {
                        $brand_url = isset($role) ? url($role->login_redirect_path) : route('home');
                    }
                @endphp
                <a class="navbar-brand @if(\Storage::disk('public')->exists('logo.jpeg')) navbar-logo @endif"
                    href="{{ $brand_url }}">
                    @if(\Storage::disk('public')->exists('logo.jpeg'))
                        <img src="{{ tenant_asset('logo.jpeg') }}" alt="{{ __('messages.logo') }}" >
                    @else
                        {{ App\Settings::get_value('school_name') }}
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <i class="fa fa-reorder"></i>
                </button>

                @if($user)
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="nav navbar-nav mr-auto" style="width:90%;display:none;" id="search_section">
                            <li style="width:90%">
                                <div class="nav-link">
                                    <form action="{{ route('student.search') }}" class="mb-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="search" id="nav-search-field" value="">
                                            <button class="btn" type="button" id="nav-search-close" style="background:#ffffff;"><i class="fa fa-close"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        </ul>

                        <!-- Left Side Of Navbar -->
                        @include('partials.header-menu')
                        <!-- Right Side Of Navbar -->
                        @include('partials.user-profile')
                    </div>
                @endif
            </nav>
        </header>

        <div id="page-wrapper" class="gray-bg">
            <div class="wrapper wrapper-content animated fadeIn">
                @yield('content')
            </div>
        </div>
        <footer>
         
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
                    <select class="form-control" data-url="{{route('change-language')}}" id="changeLanguage">
                        <option value="en" <?php if(app()->getLocale() == 'en') echo 'selected'; ?>>English</option>
                        <option value="ja" <?php if(app()->getLocale() == 'ja') echo 'selected'; ?>>Japanese</option>
                    </select>
                    <div class="pull-right">
                    <strong>100ピー</strong>
                </div>
	        </div>
	        
            </div>
            <div class="clear"></div>
            

        </footer>
    </div>
    @stack('modals')
</body>
</html>

