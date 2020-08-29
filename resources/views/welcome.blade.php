{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Union Partners
                </div>
            </div>
        </div>
    </body>
</html> --}}

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>UnionP-CRM</title>

        <meta name="description" content="Uino partners">
        <meta name="author" content="unionp">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Uino partners">
        <meta property="og:site_name" content="unionp">
        <meta property="og:description" content="Uino partners">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">

</head>
<body>

<div id="page-container" class="main-content-boxed">
    <!-- Main Container -->
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-image" style="background-image: url('assets/media/photos/photo36@2x.jpg');">
            <div class="hero bg-black-75 overflow-hidden">
                <div class="hero-inner">
                    <div class="content content-full text-center">
                        <div class="mb-5 invisible" data-toggle="appear" data-class="animated fadeInDown">
                            <i class="fa fa-circle-notch fa-3x text-primary"></i>
                        </div>
                        <h1 class="display-4 font-w600 mb-3 text-white invisible" data-toggle="appear" data-class="animated fadeInDown">
                            Union <span class="font-w300">Partners</span>
                        </h1>
                        <span class="m-2 d-inline-block invisible" data-toggle="appear" data-class="animated fadeInUp" data-timeout="600">
                            <a class="btn btn-success px-4 py-2" data-toggle="click-ripple" href="{{ route('admin.login')}}">
                                <i class="fa fa-sign-in-alt mr-1"></i> Login
                            </a>
                        </span>
                        <span class="m-2 d-inline-block invisible" data-toggle="appear" data-class="animated fadeInUp" data-timeout="600">
                            <a class="btn btn-primary px-4 py-2" data-toggle="click-ripple" href="#">
                                <i class="fa fa-fw fa-rocket mr-1"></i> Live Preview
                            </a>
                        </span>
                    </div>
                </div>
                <div class="hero-meta">
                    <div class="js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="450">
                        <span class="d-inline-block animated slideInDown infinite">
                            <i class="fa fa-angle-down text-white-50 fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
    </main>
    <!-- END Main Container -->
    </div>
    <script src="{{asset('assets/js/oneui.core.min.js')}}"></script>
    <script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
</body>
</html>
