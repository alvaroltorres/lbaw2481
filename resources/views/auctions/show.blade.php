<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BidZenith - Explore Auctions</title>
    <link rel="stylesheet" href="{{ asset('css/auctions.css') }}">
</head>
<body>

@include('partials.header')

<div class="auction-card">
    <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
    <h2>{{ $auction->title }}</h2>
    <p>{{ Str::limit($auction->description, 100) }}</p>
    <p>Current Bid: ${{ number_format($auction->current_price, 2) }}</p>
    <p>Minimum bid increment: ${{ number_format($auction->minimum_bid_increment, 2) }}</p>
</div>

<form action="{{ route('bids.store', $auction) }}" method="POST">
    @csrf
    <input type="hidden" name="auction_id" value="{{ $auction->auction_id }}">
    <div>
        <label for="price">Bid Price:</label>
        <input type="number" name="price" id="price" required>
    </div>
    @if ($errors->has('price'))
        <div class="error">{{ $errors->first('price') }}</div>
    @endif
    <div>
        <button type="submit">Place Bid</button>
    </div>
</form>

@include('partials.footer')

<script src="{{ asset('js/auctions.js') }}"></script>
</body>
</html>
