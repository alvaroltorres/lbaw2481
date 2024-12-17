<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- titulo -->
    <title>{{ config('app.name', 'BidZenith') }}</title>

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body class="font-sans antialiased">
<!-- header já incluído  -->
@include('partials.header')

<!-- conteudo da pagina -->
<main>
    @yield('content')
</main>

@include('partials.footer')

<!-- scripts -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/notifications.js') }}"></script>
<script src="{{ asset('js/add_credits.js') }}"></script>
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
