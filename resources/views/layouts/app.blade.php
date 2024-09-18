<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WAKI Indonesia Admin') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

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
        @yield('content')
    </div>

    <script src="{{ asset('css/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/misc.js') }}"></script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>
</body>
</html>

