<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Language" content="en,es">
    <meta name="description" content="Garafy.es Encuentra tu taller en línea las 24 horas del dia">
    <meta name="keywords" content="taller,cita,españa,repuestos,motor,coches,reparación">
    <meta name="language" content="es-ES">
    <meta name="author" content="garafy">
    <link rel="shortcut icon" href="{!! url('assets/img/favicon.png') !!}">
    <title>{{env("APP_NAME")}} - @yield('title')</title>
    @stack('head')
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js" integrity="sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP" crossorigin="anonymous"></script>
</head>
<body>

<div id="app">
    @yield('content')
    <footer-component></footer-component>
</div>

<script src="{{ mix('assets/js/app.js') }}"></script>
@stack('scripts')
<script>

</script>
</body>
</html>