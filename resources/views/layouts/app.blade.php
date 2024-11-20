<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Título -->
    <title>{{ config('app.name', 'BidZenith') }}</title>

    <!-- Incluir CSS e JS via Vite -->
    @vite(['resources/css/style.css', 'resources/js/main.js'])

    <!-- Outros links como favicon, etc. -->
</head>
<body class="font-sans antialiased">
<!-- Header já incluído via partial dentro do layout -->
@include('partials.header')

<!-- Conteúdo da página -->
<main>
    @yield('content')
</main>

<!-- Rodapé, se houver -->
@include('partials.footer')
<!-- Scripts adicionais, se necessário -->
</body>
</html>
