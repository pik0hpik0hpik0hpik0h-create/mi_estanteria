<!DOCTYPE html>
<html lang="es" data-theme="estanteria">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estantería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-100">

    @include('components.alerts')

    @yield('content')

</body>
</html>