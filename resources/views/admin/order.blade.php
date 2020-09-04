{{-- @extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="content content-boxed">
    <div class="block">
        <div class="block-content block-content-full">
        <div class="block-content">
            <table class="table table-hover table-vcenter">
                <thead>
                    <tr>
                        <th style="width: 30px;"></th>
                        <th>Name</th>
                        <th style="width: 15%;">Description</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Date</th>
                    </tr>
                </thead>
                @foreach($orders as $order)
                    <tbody data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <tr>
                            <td class="text-center">
                                <i class="fa fa-angle-right text-muted"></i>
                            </td>
                            <td class="font-w600 font-size-sm">
                                <div class="py-1">
                                    <a href="be_pages_generic_profile.php">{{ $order->title }}</a>
                                </div>
                            </td>
                            <td>
                            <span class="badge badge-warning">{{ $order->description }}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                            <em class="font-size-sm text-muted">{{ $order->created_at }}</em>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="collapse" id="collapseExample">
                        <tr>
                            <td class="text-center"></td>
                            <td class="font-w600 font-size-sm">+ $224,00</td>
                            <td colspan="2">
                                <i class="fab fa-paypal"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="font-w600 font-size-sm">+ $957,00</td>
                            <td colspan="2">
                                <i class="fab fa-paypal"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="font-w600 font-size-sm">+ $625,00</td>
                            <td colspan="2">
                                <i class="fab fa-paypal"></i>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>UnionP</title>

        <meta name="description" content="UnionP">
        <meta name="author" content="UnionP">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="UnionP">
        <meta property="og:site_name" content="UnionP">
        <meta property="og:description" content="UnionP">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{asset('assets/media/favicons/favicon.png')}}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{asset('assets/media/favicons/favicon-192x192.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/media/favicons/apple-touch-icon-180x180.png')}}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Fonts and OneUI framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
        <link rel="stylesheet" id="css-main" href="{{asset('assets/css/oneui.min.css')}}">

    </head>
    <body>

        <div id="page-container">

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                <div class="bg-image" style="background-image: url('{{asset('assets/media/photos/photo6@2x.jpg')}}');">
                    <div class="hero bg-primary-dark-op">
                        <div class="hero-inner">
                            <div class="content content-full bg-black-50">
                                <div class="row justify-content-center">
                                    <div class="col-md-6 py-3 text-center">
                                        <div class="push">
                                            <a class="link-fx font-w700 font-size-h1" href="index.html">
                                                <span class="text-white">UnionP</span>
                                            </a>
                                            <p class="font-size-sm text-white-75">Stay tuned! We are working on it and it is coming soon!</p>
                                        </div>

                                        <div class="js-countdown"></div>

                                        <form class="push" action="op_coming_soon.html" method="POST" onsubmit="return false;">
                                            <div class="form-group row justify-content-center">
                                                <div class="col-md-10 col-xl-6">
                                                    <div class="input-group mb-2">
                                                        <input type="email" class="form-control border-0" placeholder="Enter your email..">
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-primary">Subscribe</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <a class="btn btn-sm btn-light" href="{{route('admin.index')}}">
                                            <i class="fa fa-arrow-left mr-1"></i> Go Back to Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>

        <script src="{{asset('assets/js/oneui.core.min.js')}}"></script>
        <script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
        <script src="{{asset('assets/js/plugins/jquery-countdown/jquery.countdown.min.js')}}"></script>
        <script src="{{asset('assets/js/pages/op_coming_soon.min.js')}}"></script>
    </body>
</html>
