<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WAKI Indonesia Admin') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendors/css/vendor.bundle.base.css') }}">
    <link rel="shortcut icon" href="{{ asset('sources/favicon.png') }}" />
</head>
<body>
    <div id="app">
        <div class="container-scroller">
            <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo" href="#"><img src="{{asset('sources/logosince.svg')}}" alt="logo" /></a>
                    <a class="navbar-brand brand-logo-mini" href="#"><img src="{{asset('sources/logo-mini.svg')}}" alt="logo" /></a>
                </div>

                <div class="navbar-menu-wrapper d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <div class="search-field d-none d-md-block">
                        <form class="d-flex align-items-center h-100" action="#">
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                </div>
                                <input type="text" class="form-control bg-transparent border-0" placeholder="Cari">
                            </div>
                        </form>
                    </div>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                <div class="nav-profile-img">
                                    <img src="{{asset('sources/testface1.jpg')}}" alt="image">
                                    <span class="availability-status online"></span>
                                </div>
                                <div class="nav-profile-text">
                                  <p class="mb-1 text-black"></p>
                                </div>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="#">
                                <i class="mdi mdi-cached mr-2 text-success"></i> Activity Log </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="login.html">
                                <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
                            </div>
                        </li>
                        <li class="nav-item nav-logout d-none d-lg-block">
                            <a class="nav-link" href="login.html">
                                <i class="mdi mdi-power"></i>
                            </a>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>

            <div class="container-fluid page-body-wrapper">
                @include("admin.layouts.navigation")

                @yield('content')
            </div>

            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                  <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2020 <a href="#" target="_blank">WAKi International Group</a>. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('css/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('css/vendors/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/misc.js') }}"></script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>

    <script src="{{asset('js/file-upload.js')}}"></script>
    @yield('script')
</body>
</html>