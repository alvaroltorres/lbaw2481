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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts (principais) -->
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/add_credits.js') }}" defer></script>

    <!-- Notificações -->
    <script src="{{ asset('js/notifications.js') }}" defer></script>

    <!-- Traduções e placeholders para notificações -->
    <script>
        window.translations = {
            notification: {
                newBidOwner: @json(__('New bid of €:amount on your auction ":auction" by :bidder!')),
                newBidParticipant: @json(__('A new bid of €:amount was placed on the auction ":auction" by :bidder, which you are participating in!')),
                viewDetails: @json(__('View Details')),
                errorFetching: @json(__('Error fetching notifications')),
                errorMarkingRead: @json(__('Error marking notification as read')),

                auctionEndingOwner: @json(__('Your Auction ":auction" is ending soon (ends at :end_time).')),
                auctionEndingParticipant: @json(__('Auction ":auction" you are participating in is ending soon (ends at :end_time).')),

                auctionEndedOwner: @json(__('Your Auction ":auction" has ended.')),
                auctionEndedParticipant: @json(__('Auction ":auction" you were participating in has ended.')),

                auctionCanceledOwner: @json(__('Your Auction ":auction" was canceled. Reason: :reason')),
                auctionCanceledParticipant: @json(__('An Auction ":auction" you follow was canceled. Reason: :reason')),

                auctionWinnerOwner: @json(__('A winner has been determined for your auction ":auction"! Winner: :winner.')),
                auctionWinnerParticipant: @json(__('Auction ":auction" has a winner, and it is :winner.')),
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
