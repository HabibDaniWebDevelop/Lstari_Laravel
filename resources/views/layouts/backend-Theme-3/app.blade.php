<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr" data-theme="theme-default"
    data-assets-path="{!! asset('assets/sneatV1/assets/') !!}" data-template="horizontal-menu-template-dark">

<head>
    <!-- Required meta tags always come first -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <link rel="icon" type="image/png" sizes="16x16" href="{!! asset('assets/images/favicon.png') !!}">
    <title>Lestari | {{ $title }} </title>
    <meta name="description" content="PT Lestari">
    <meta name="keywords" content="PT Lestari ">

    @include('layouts.backend-Theme-3.stylesheet')
    @yield('css')
</head>

<body class="__fb-dark-mode">


    @include('layouts.backend-Theme-3.preloader')

    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">

            <!-- Navbar -->
            @include('layouts.backend-Theme-3.navbar')
            <!-- / Navbar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Menu -->
                    @include('layouts.backend-Theme-3.menu')
                    <!-- / Menu -->

                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p py-2">
                        @yield('Dashboard')
                        @yield('container')
                    </div>
                    <!--/ Content -->

                    <!-- Footer -->
                    @include('layouts.backend-Theme-3.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>

            <!--/ Layout container -->
        </div>
    </div>

    <!--/ Layout wrapper -->
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"
        style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
    </div>

    @include('layouts.backend-Theme-3.javascript')
    @yield('script')

</body>

</html>

@include('layouts.backend-Theme-3.XtraScript')
@include('layouts.backend-Theme-3.XtraScriptmessanging')