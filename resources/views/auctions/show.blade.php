@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Auction Details -->
        <div class="auction-card">
            <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ __($auction->title) }}">
            <h2>{{ __($auction->title) }}</h2>
            <p>{{ __($auction->description) }}</p>
            <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
            <p>{{ __('Minimum Bid Increment') }}: ${{ number_format($auction->minimum_bid_increment, 2) }}</p>

            @auth
                @if (auth()->user()->user_id === $auction->user_id)
                    <!-- Edit and Delete Buttons for Auction Owner -->
                    <a href="{{ route('auctions.edit', $auction) }}" class="btn btn-primary">{{ __('Edit Auction') }}</a>

                    <form action="{{ route('auctions.destroy', $auction) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this auction?') }}');">{{ __('Delete Auction') }}</button>
                    </form>
                @else
                    <!-- Bid Form for Other Users -->
                    <form action="{{ route('bids.store', $auction) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="price">{{ __('Bid Price') }}:</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" required placeholder="Enter price">
                            @error('price')
                            <div class="text-danger">{{ __($message) }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Place Bid') }}</button>
                    </form>
                @endif
            @endauth
        </div>

        <!-- Link to Bidding History -->
        <a href="{{ route('auctions.biddingHistory', $auction) }}" class="btn btn-info mt-3">{{ __('View Bidding History') }}</a>
    </div>
@endsection
