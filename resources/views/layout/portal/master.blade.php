<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <!-- <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="black">
    <title>PRESTIGE INTERACTIVE</title>
    <link rel="shortcut icon" href="{{ URL::to('images/prestige-favicon.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::to('client/assets/images/favicon.ico') }}">
    <!-- Bootstrap Css -->
    <link href="{{ URL::to('client/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::to('client/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::to('plugins/toastr/toastr.min.css') }}">
    <!-- App Css-->
    <link href="{{ URL::to('client/assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::to('client/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- <link rel="stylesheet" href="{{ URL::to('js/jquery-ui/jquery-ui.min.css') }}"> -->
    <link rel="stylesheet" href="{{ URL::to('css/custom-portal.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::to('plugins/toastr/toastr.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::to('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/custom.css') }}">
</head>

<body data-topbar="dark" data-layout="horizontal">
    <div class="wrapper" id="app">
        <!-- Header Navbar -->
        @include('layout.portal.header-nav')
        <!-- /.navbar -->
        <div class="main-content">

            <!-- <div class="page-content"> -->
            <div class="">
                <div class="container-fluid">
                    <!-- <div class="row"> -->
                    <div>
                        @yield('content')
                    </div>
                    <!-- </div>  -->
                    <!-- end row -->
                </div> <!-- container-fluid -->
            </div>

            <!-- End Page-content -->
        </div>
        <!-- Main Footer -->
        <loader :is-visible="isLoading"></loader>
        <!-- @include('layout.portal.footer') -->
    </div>
    <!-- ./wrapper -->
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <!-- <script src="{{ URL::to('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
    <!-- Toastr -->
    <script src="{{ URL::to('plugins/toastr/toastr.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ URL::to('plugins/chart.js/Chart.min.js') }}"></script>
    
    <!-- AdminLTE App -->
    <script src="{{ URL::to('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ URL::to('js/helper.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <!-- Jquery UI-->
    <script src="{{ URL::to('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- <script src="{{ URL::to('dist/js/portallte.min.js') }}"></script> -->
    <script src="{{ URL::to('js/helper.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="{{ URL::to('client/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::to('client/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::to('client/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::to('client/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Morris Chart-->
    <script src="{{ URL::to('client/assets/libs/morris.js/morris.min.js') }}"></script>
    <script src="{{ URL::to('client/assets/libs/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::to('client/assets/js/pages/dashboard.init.js') }}"></script>
    <script>
        addEventListener("load", function() {
            // Set a timeout...
            setTimeout(function() {
                // Hide the address bar!
                window.scrollTo(0, 1);
            }, 0);
        });
        document.addEventListener("gesturestart", function(e) {
            e.preventDefault();
            document.body.style.zoom = 0.99;
        });

        document.addEventListener("gesturechange", function(e) {
            e.preventDefault();

            document.body.style.zoom = 0.99;
        });
        document.addEventListener("gestureend", function(e) {
            e.preventDefault();
            document.body.style.zoom = 1;
        });
    </script>
    @stack('scripts') <!-- To include script links -->
</body>

</html>