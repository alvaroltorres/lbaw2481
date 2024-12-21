<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Account Blocked') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Adicione outros estilos conforme necessário -->
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="alert alert-danger text-center p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">{{ __('Account Blocked') }}</h1>
    <p>{{ __('Your account has been blocked. Please contact support for more information.') }}</p>
</div>
</body>
</html>
