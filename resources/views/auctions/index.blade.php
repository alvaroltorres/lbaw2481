<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BidZenith - Explore Auctions</title>
    <link rel="stylesheet" href="{{ asset('css/auctions.css') }}">
</head>
<body>

@include('partials.header')

<main>
    <section class="auctions">
        <div class="container">
            <h1>Explore Auctions</h1>
            <div class="auction-grid">
                @foreach($activeauctions as $auction)
                    <div class="auction-card">
                        <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
                        <h2>{{ $auction->title }}</h2>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>Current Bid: ${{ number_format($auction->current_price, 2) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">View Auction</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

@include('partials.footer')

<script src="{{ asset('js/auctions.js') }}"></script>
</body>
</html>
