<!DOCTYPE html>
<html lang="es" data-theme="estanteria" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estantería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-100">

    @include('components.alerts')

    @yield('content')

<script>
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => {
            document.querySelectorAll(".alert").forEach(el => {
                el.style.transition = "opacity 0.5s";
                el.style.opacity = "0";
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    });
</script>

</body>
</html>