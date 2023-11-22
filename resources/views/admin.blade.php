<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @viteReactRefresh
        @vite('resources/js/app.js')
{{--        @vite(asset('sm-laravel-admin/resources/js/app.js'))--}}
{{--        <script type="module" src="sm-laravel-admin/resources/js/app.js"></script>--}}
    </head>
    <body class="font-sans antialiased">
        <div id="app"></div>
    </body>
</html>
