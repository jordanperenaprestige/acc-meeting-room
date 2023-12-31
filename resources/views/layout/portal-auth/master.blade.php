<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <!-- <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="black">
    <title>PRESTIGE INTERACTIVE | LOGIN</title>
    <link rel="shortcut icon" href="{{ URL::to('images/prestige-favicon.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ URL::to('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::to('plugins/toastr/toastr.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::to('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/custom.css') }}">
</head>

<body class="hold-transition login-page">
    @yield('content')

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ URL::to('plugins/toastr/toastr.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::to('dist/js/adminlte.min.js') }}"></script>
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
</body>

</html>