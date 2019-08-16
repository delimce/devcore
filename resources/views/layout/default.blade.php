<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="my-token" content="null">
    <link rel="shortcut icon" href="{!! url('assets/img/favicon.png') !!}">
    <title>{{env("APP_NAME")}} - @yield('title')</title>
    @stack('head')
    <link rel="stylesheet" href="{!! url('assets/css/app.css') !!}">
</head>
<body>

<div id="app">
    @yield('content')
</div>
<!--Footer-->
<footer>
    <!--Copyright-->

    <!--/.Copyright-->
</footer>
<!--/.Footer-->
<script src="{!! url('assets/js/app.js') !!}"></script>
@stack('scripts')
<script>

</script>
</body>
</html>