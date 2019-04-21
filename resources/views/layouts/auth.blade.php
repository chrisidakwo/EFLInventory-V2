<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield("title") | {{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
    <main class="d-flex align-items-center">
        @yield('content')
    </main>

    <script src="{{ asset("vendors/jquery/jquery.min.js") }}"></script>
    <script src="{{ asset("vendors/popper/popper.min.js") }}"></script>
    <script src="{{ asset("vendors/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
</body>
</html>
