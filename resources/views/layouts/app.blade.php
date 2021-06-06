<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('layouts.links')
        <title>Interview Task</title>
    </head>
    <body>
        <div class="">
            @yield('content')
        </div>
        @include('layouts.script')
        @yield('script')
    </body>
</html>
