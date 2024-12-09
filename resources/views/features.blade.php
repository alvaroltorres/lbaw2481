@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Main Features') }}</h1>
        <section class="features-section">
            <h2>{{ __('General Functionalities') }}</h2>
            <ul>
                <li><strong>{{ __('View active auctions:') }}</strong> {{ __('Allows users to view active auctions with relevant information such as the title, description, starting price, and remaining time.') }}</li>
                <li><strong>{{ __('Access user profiles:') }}</strong> {{ __('Users can access other users\' profiles to view their feedback and bidding history.') }}</li>
                <li><strong>{{ __('Browse auctions by category:') }}</strong> {{ __('Allows browsing auctions by predefined categories (e.g., technology, art, fashion).') }}</li>
                <li><strong>{{ __('Search auctions:') }}</strong> {{ __('Users can search auctions based on simple and advanced criteria, including title, category, price range, and end date.') }}</li>
                <li><strong>{{ __('Create auctions:') }}</strong> {{ __('Authenticated sellers can create new auctions by providing a title, description, starting price, images, category, and auction duration.') }}</li>
            </ul>

            <h2>{{ __('Functionalities for Authenticated Users') }}</h2>
            <ul>
                <li><strong>{{ __('Follow auctions:') }}</strong> {{ __('Authenticated users can follow auctions of interest to receive updates and notifications.') }}</li>
                <li><strong>{{ __('View followed auctions:') }}</strong> {{ __('Access to a list of followed auctions to easily monitor them.') }}</li>
                <li><strong>{{ __('View bidding history:') }}</strong> {{ __('Users can view the complete history of bids made on each auction.') }}</li>
                <li><strong>{{ __('Manage created auctions:') }}</strong> {{ __('Sellers can edit or cancel auctions as long as they haven’t received bids yet.') }}</li>
            </ul>

            <h2>{{ __('Bidding Functionalities') }}</h2>
            <ul>
                <li><strong>{{ __('Place bids on active auctions:') }}</strong> {{ __('Authenticated buyers can place bids on active auctions, with automatic bid increments when necessary.') }}</li>
                <li><strong>{{ __('View bidding history in an auction:') }}</strong> {{ __('Allows users to view all bids placed in an auction, including date, amount, and user.') }}</li>
                <li><strong>{{ __('Extend auction time:') }}</strong> {{ __('If a bid is placed within the last 15 minutes, the auction time is automatically extended to ensure competitiveness.') }}</li>
            </ul>

            <h2>{{ __('Notifications') }}</h2>
            <ul>
                <li><strong>{{ __('Notification of new bids on owned or followed auctions:') }}</strong> {{ __('Users receive notifications whenever a new bid is placed on an auction they own or follow.') }}</li>
                <li><strong>{{ __('Notifications about won, lost, or canceled auctions:') }}</strong> {{ __('Notifications are sent to users informing them if they won or lost an auction, or if an auction was canceled.') }}</li>
                <li><strong>{{ __('Auction end notifications:') }}</strong> {{ __('Users receive notifications when an auction they are involved in ends.') }}</li>
                <li><strong>{{ __('Notification for the auction winner:') }}</strong> {{ __('The auction winner is notified as soon as the auction ends.') }}</li>
            </ul>

            <h2>{{ __('Other Features') }}</h2>
            <ul>
                <li><strong>{{ __('Push notifications via email or app:') }}</strong> {{ __('Users can choose to receive push notifications via the app or email to stay updated on bids and auctions.') }}</li>
                <li><strong>{{ __('Dark mode:') }}</strong> {{ __('Option to switch to dark mode, improving the experience in low-light environments.') }}</li>
                <li><strong>{{ __('Multilingual support (Portuguese and English):') }}</strong> {{ __('The platform offers support for multiple languages, including Portuguese and English.') }}</li>
            </ul>
        </section>
    </div>
@endsection