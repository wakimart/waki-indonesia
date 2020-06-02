<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
    @yield('style')
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
                                <input type="text" class="form-control bg-transparent border-0" placeholder="Search">
                            </div>
                        </form>
                    </div>

                    <!-- For Change Password -->
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->has('new-password'))
                    @foreach($errors->get('new-password') as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                    @endforeach
                    @endif

                    <!-- For Saved/Changed Data -->
                    @if (session()->has('message'))
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#modal-Notification").modal("show");
                            $("#txt-notification").html("<div class=\"alert alert-success\">{{ session()->get('message')}}</div>");
                        });
                    </script>
                    @endif

                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                <div class="nav-profile-img">
                                    <img src="{{asset('sources/favicon.png')}}" alt="image">
                                    <span class="availability-status online"></span>
                                </div>
                                <div class="nav-profile-text">
                                  <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                                </div>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                <i class="mdi mdi-cached mr-2 text-success"></i> Activity Log </a>
                                <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="{{route('admin_logout')}}">
                                <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
                            </div>
                        </li>
                        <li class="nav-item nav-logout d-none d-lg-block">
                            <a class="nav-link" href="{{route('admin_logout')}}">
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
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item nav-profile" >
                            <a href="#" class="nav-link">
                                <div class="nav-profile-image">
                                    <img src="{{asset('sources/favicon.png')}}" alt="profile">
                                    <span class="login-status online"></span>
                                    <!--change to offline or busy as needed-->
                                </div>
                                <div class="nav-profile-text d-flex flex-column">
                                    <span class="font-weight-bold mb-2">Welcome, {{ Auth::user()->name }}</span>
                                    <span class="text-secondary text-small">Admin WAKI Indonesia</span>
                                </div>
                                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                                <span class="text-secondary text-small"><a href="#" data-toggle="modal" data-target="#modal-ChangePassword">Change Password</a></span>
                            </a>
                        </li>

                        @include("admin.layouts.navigation")
                        <li class="nav-item">
                          <a class="nav-link" href="{{route('admin_logout')}}">
                          <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
                        </li>
                    </ul>
                </nav>

                @yield('content')
            </div>

            <!-- modal notification (data saved) -->
            <div class="modal fade" role="dialog" tabindex="-1" id="modal-Notification">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Notice</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="txt-notification"></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="button" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- modal ganti password -->
            <div class="modal fade" role="dialog" tabindex="-1" id="modal-ChangePassword">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <!-- form untuk ganti password -->
                        <form method="post" action="{{ route('changePassword') }}">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="form-group">
                                    <span style="display:block;">Current Password</span>
                                    <input class="form-control form-control" id="current-password" type="password" name="current-password" placeholder="Current Password" required>
                                    <span class="invalid-feedback">
                                        <strong>Your current password does not matches with the password you provided.</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <span style="display:block;">New Password</span>
                                    <input class="form-control form-control" id="new-password" type="password" name="new-password" required placeholder="Min length: 6">
                                    <span class="invalid-feedback">
                                        <strong>New Password cannot be same as your current password.</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <span style="display:block;">Confirm New Password</span>
                                    <input class="form-control form-control" id="new-password-confirm" type="password" name="new-password_confirmation" placeholder="Confirm New Password" required>
                                    <span class="invalid-feedback">
                                        <strong>This field must same with your New Password.</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light" type="button" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                  <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2020 <a href="#" target="_blank">WAKi International Group</a>. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>


    @yield('script')
    <script src="{{ asset('css/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('css/vendors/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/misc.js') }}"></script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>

    <script src="{{asset('js/file-upload.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#current-password").on("keyup", function () {
                console.log("tes");
                CheckChangePassword();
            });

            $("#new-password").on("keyup", function () {
                console.log("tes1");
                CheckChangePassword();
            });

            $("#new-password-confirm").on("keyup", function () {
                console.log("tes2");
                CheckChangePassword();
            });
        });

        function CheckChangePassword()
        {
            var currentPass = $("#current-password").val();
            var newPass = $("#new-password").val();
            var confirmPass = $("#new-password-confirm").val();

            console.log("cp: " + currentPass + ", np: " + newPass + ", cfrpas: " + confirmPass);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{route('check-change-password')}}",
                data: {
                    'currentPass': currentPass,
                    'newPass': newPass,
                    'confirmPass': confirmPass,
                },
                success: function (data) {
                    $.each(data, function (key, val)
                    {
                        if (val != '')
                        {
                            $("#" + key).addClass("is-invalid");
                        } else
                        {
                            $("#" + key).removeClass("is-invalid");
                        }
                    });
                },
                error: function( error )
                {
                    console.log(error);
                }
            });
        }
    </script>
</body>
</html>
