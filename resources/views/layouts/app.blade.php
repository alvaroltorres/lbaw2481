<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Metas básicas -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BidZenith') }}</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

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
@include('partials.header')
<main>
    @yield('content')
</main>

@include('partials.footer')

@yield('scripts')
</body>
</html>
