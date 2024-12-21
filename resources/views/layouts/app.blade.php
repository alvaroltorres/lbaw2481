<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Metas básicas -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'BidZenith') }}</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>
    <script src="{{ asset('js/add_credits.js') }}" defer></script>
    <script>
        window.translations = {
            notification: {
                newBid: @json(__('A new bid of €:amount has been placed on your auction: :auction by :bidder.')),
                viewDetails: @json(__('View Details')),
                errorFetching: @json(__('Error fetching notifications')),
                errorMarkingRead: @json(__('Error marking notification as read'))
            }
        };
    </script>

    @yield('head')
</head>
<body class="font-sans antialiased">
<!-- Header já incluído -->
@include('partials.header')

<!-- Conteúdo da página -->
<main>
    @yield('content')
</main>

<!-- Footer -->
@include('partials.footer')

<!-- Scripts -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/notifications.js') }}"></script>§
<script src="{{ asset('js/add_credits.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.translations = {
        notification: {
            newBid: @json(__('A new bid of €:amount has been placed on your auction: :auction by :bidder.')),
            viewDetails: @json(__('View Details')),
            errorFetching: @json(__('Error fetching notifications')),
            errorMarkingRead: @json(__('Error marking notification as read'))
        }
    };
</script>

@yield('scripts')
</body>
</html>
