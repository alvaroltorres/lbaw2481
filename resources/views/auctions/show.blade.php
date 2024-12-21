@extends('layouts.app')

@section('content')
    <div class="container auction-page">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="auction-meta-container" style="margin-top:1rem; margin-bottom:1rem; padding:1rem; border:1px solid #ddd; border-radius:8px;">
            <img src="{{ asset('images/auctions/' . ($auction->image ?? 'default.png')) }}" alt="{{ $auction->title }}" class="auction-image">
            <h2 class="auction-title">{{ $auction->title }}</h2>
            <p class="auction-description">{{ $auction->description }}</p>
            <p class="status">{{ __($auction->status) }}</p>
            <p class="auction-meta">
                <strong>{{ __('Ends on') }}:</strong>
                {{ $auction->ending_date->format('d/m/Y H:i') }}
            </p>
            <p class="auction-meta">
                <strong>{{ __('Seller') }}:</strong>
                <a href="{{ route('user.show', $auction->seller->user_id) }}">
                    {{ $auction->seller->fullname }}
                </a>
            </p>
            <p class="auction-meta">
                <strong>{{ __('Current Bid') }}:</strong>
                €{{ number_format($auction->current_price, 2, ',', '.') }}
            </p>
            <p class="auction-meta">
                <strong>{{ __('Minimum Bid Increment') }}:</strong>
                €{{ number_format($auction->minimum_bid_increment, 2, ',', '.') }}
            </p>
        </div>

        @auth
            @if (auth()->user()->user_id === $auction->user_id)
                <div class="owner-actions">
                    <a href="{{ route('auctions.edit', $auction) }}" class="btn btn-primary">
                        {{ __('Edit Auction') }}
                    </a>
                    <!-- Encerrar agora (sem escolher lances) -->
                    <form action="{{ route('auctions.endManually', $auction) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            {{ __('End Auction Now') }}
                        </button>
                    </form>
                    <!-- Cancel Auction -->
                    <form action="{{ route('auctions.cancel', $auction) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-dark" onclick="return confirm('{{ __('Are you sure you want to cancel this auction?') }}');">
                            {{ __('Cancel Auction') }}
                        </button>
                    </form>
                    <!-- Deletar do BD -->
                    <form action="{{ route('auctions.destroy', $auction) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('{{ __('Are you sure you want to delete this auction?') }}');">
                            {{ __('Delete Auction') }}
                        </button>
                    </form>
                </div>
            @else
                <!-- Para quem não é o dono: formulário para dar lance, seguir, etc. -->
                <form action="{{ route('bids.store', $auction) }}" method="POST" class="bid-form">
                    @csrf
                    <div class="form-group">
                        <label for="price">{{ __('Bid Price') }} (€):</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" required
                               placeholder="{{ __('Enter your bid') }}">
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn">{{ __('Place Bid') }}</button>
                </form>

                <form action="{{ route('messages.start') }}" method="POST" style="display:inline-block; margin-left:1em;">
                    @csrf
                    <input type="hidden" name="auction_id" value="{{ $auction->auction_id }}">
                    <button type="submit" class="btn-link text-secondary p-0" style="border: none; background: none; cursor: pointer;">
                        {{ __('Contact Seller') }}
                    </button>
                </form>

                @if($isFollowed)
                    <form action="{{ route('auction.unfollow', ['auction_id' => $auction->auction_id]) }}"
                          method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Unfollow Auction') }}</button>
                    </form>
                @else
                    <form action="{{ route('auction.follow', ['auction_id' => $auction->auction_id]) }}"
                          method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-primary">{{ __('Follow Auction') }}</button>
                    </form>
                @endif
            @endif
        @endauth

        <a href="{{ route('auctions.biddingHistory', $auction) }}" class="btn btn-info mt-3">
            {{ __('View Bidding History') }}
        </a>
    </div>
@endsection
