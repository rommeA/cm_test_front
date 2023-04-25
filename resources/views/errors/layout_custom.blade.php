<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{--    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">--}}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @yield('after_styles')
</head>
<body>
@yield('content')
</body>


