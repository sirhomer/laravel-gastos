<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gastos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        {{-- Your Vue components will render here --}}
    </div>
    <!-- CDN fallback for Bootstrap JS so offcanvas works without a local npm build -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha256-+s9YqQj6a0m9r5fHj3yqf3nXy+zPZ6X8m1jzE2uZk4k=" crossorigin="anonymous"></script>
</body>
</html>