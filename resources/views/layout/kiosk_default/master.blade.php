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
    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ URL::to('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/custom-kiosk.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/softkeys-0.0.1.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/jquery.zoom.css') }}">
    <!-- Set Custom Theme UI -->
    <link rel="stylesheet" href="{{ URL::to('css') }}/{{ $site_name }}-theme.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::to('plugins/toastr/toastr.min.css') }}">
</head>

<body>
    <!-- LOADING VIDEO -->
    <div id="app" app-env="{{ config('app.env') }}">
        <!-- <img src="/assets/images/bg-kiosk.png" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">  -->

        @yield('content')

        <loader :is-visible="isLoading"></loader>
    </div>

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::to('js/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ URL::to('js/zoom.jquery.js') }}"></script>
    <script src="{{ URL::to('js/panzoom.js') }}"></script>
    <script src="{{ URL::to('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/popper.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/softkeys-0.0.1.js') }}"></script>
    <script src="{{ URL::to('js/way-finding.js') }}"></script>
    <script src="{{ URL::to('js/helper.js') }}"></script>
    <script src="{{ URL::to('js/autoSizr.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ URL::to('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
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