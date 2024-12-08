@extends('layouts.app')

@section('content')
    <h1>Followed Auctions</h1>

    @if($auctions->isEmpty())
        <p>You are not following any auctions.</p>
    @else
        <div class="auction-grid">
            @forelse($auctions as $auction)
                <div class="auction-card">
                    <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
                    <h2>{{ $auction->title }}</h2>
                    <p>{{ Str::limit($auction->description, 100) }}</p>
                    <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
                    <p>
                        <strong>{{ __('Seller:') }}</strong>
                        <a href="{{ route('user.show', $auction->seller->user_id) }}">
                            {{ $auction->seller->fullname }}
                        </a>
                    </p>
                    <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>

                </div>
            @empty
                <p>{{ __('No auctions found for the applied filters.') }}</p>
            @endforelse
        </div>
    @endif
@endsection
