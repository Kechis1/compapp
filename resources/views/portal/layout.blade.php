<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="app">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Comparison') . ' - ' . $title }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

        <!-- Styles -->
        <link href="{{ asset('css/frontend.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body class="{{ $page_name }}">

        @include('portal.includes.header')

        @yield('content')

        @include('portal.includes.footer')

        @include('portal.includes.guide')

    </body>
</html>