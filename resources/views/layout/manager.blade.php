<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Language" content="en,es">
    <link rel="shortcut icon" href="{!! url('assets/img/favicon.png') !!}">
    <title>{{env("APP_NAME")}} - @yield('title')</title>
    @stack('head')
    <link rel="stylesheet" href="{!! url('assets/css/app.css') !!}">
    <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js" integrity="sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP" crossorigin="anonymous"></script>
</head>
<body>

<div id="app">
    @yield('content')
</div>

<script src="{!! url('assets/js/app.js') !!}"></script>
@stack('scripts')

</body>
</html>