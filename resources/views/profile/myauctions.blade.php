@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('My Auctions') }}</h1>

        <!-- Active Auctions -->
        <h3>{{ __('Active Auctions') }}</h3>
        @if($myauctions->where('status', 'Active')->count() > 0)
            <div class="auction-grid" style="margin-top:1rem; gap:1rem; display:flex; flex-wrap:wrap;">
                @foreach($myauctions->where('status', 'Active') as $auction)
                    <div class="auction-card" style="border:1px solid #ddd; padding:1rem; border-radius:8px; width:250px;">
                        @if($auction->image)
                            <img src="{{ asset('storage/images/auctions/'. $auction->image) }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @else
                            <img src="{{ asset('storage/images/auctions/default.png') }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @endif
                        <h2>{{ $auction->title }}</h2>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>{{ __('Current Bid') }}: €{{ number_format($auction->current_price, 2) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">
                            {{ __('View Auction') }}
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p>{{ __('You have no active auctions.') }}</p>
        @endif

        <!-- Upcoming Auctions -->
        <h3>{{ __('Upcoming Auctions') }}</h3>
        @if($myauctions->where('status', 'Upcoming')->count() > 0)
            <div class="auction-grid" style="margin-top:1rem; gap:1rem; display:flex; flex-wrap:wrap;">
                @foreach($myauctions->where('status', 'Upcoming') as $auction)
                    <div class="auction-card" style="border:1px solid #ddd; padding:1rem; border-radius:8px; width:250px;">
                        @if($auction->image)
                            <img src="{{ asset('storage/images/auctions/'. $auction->image) }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @else
                            <img src="{{ asset('storage/images/auctions/default.png') }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @endif
                        <h2>{{ $auction->title }}</h2>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>{{ __('Starting Bid') }}: €{{ number_format($auction->current_price, 2) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">
                            {{ __('View Auction') }}
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p>{{ __('You have no upcoming auctions.') }}</p>
        @endif

        <!-- Unsold Auctions -->
        <h3>{{ __('Unsold Auctions') }}</h3>
        @if($myauctions->where('status', 'Unsold')->count() > 0)
            <div class="auction-grid" style="margin-top:1rem; gap:1rem; display:flex; flex-wrap:wrap;">
                @foreach($myauctions->where('status', 'Unsold') as $auction)
                    <div class="auction-card" style="border:1px solid #ddd; padding:1rem; border-radius:8px; width:250px;">
                        @if($auction->image)
                            <img src="{{ asset('storage/images/auctions/'. $auction->image) }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @else
                            <img src="{{ asset('storage/images/auctions/default.png') }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @endif
                        <h2>{{ $auction->title }}</h2>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">
                            {{ __('View Auction') }}
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p>{{ __('You have no unsold auctions.') }}</p>
        @endif

        <!-- Closed (Canceled) Auctions -->
        <h3>{{ __('Closed (Canceled) Auctions') }}</h3>
        @if($myauctions->where('status', 'Closed')->count() > 0)
            <div class="auction-grid" style="margin-top:1rem; gap:1rem; display:flex; flex-wrap:wrap;">
                @foreach($myauctions->where('status', 'Closed') as $auction)
                    <div class="auction-card" style="border:1px solid #ddd; padding:1rem; border-radius:8px; width:250px;">
                        @if($auction->image)
                            <img src="{{ asset('storage/images/auctions/'. $auction->image) }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @else
                            <img src="{{ asset('storage/images/auctions/default.png') }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @endif
                        <h2>{{ $auction->title }}</h2>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">
                            {{ __('View Auction') }}
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p>{{ __('You have no closed (canceled) auctions.') }}</p>
        @endif
    </div>
@endsection
