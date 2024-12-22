@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Sold Auctions') }}</h1>

        @if($soldAuctions->count() > 0)
            <div class="auction-grid" style="margin-top:1rem; gap:1rem; display:flex; flex-wrap:wrap;">
                @foreach($soldAuctions as $auction)
                    <div class="auction-card" style="border:1px solid #ddd; padding:1rem; border-radius:8px; width:250px;">
                        @if($auction->image)
                            <img src="{{ asset('storage/images/auctions/'. $auction->image) }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @else
                            <img src="{{ asset('storage/images/auctions/default.png') }}" alt="{{ __($auction->title) }}" class="auction-image">
                        @endif
                        <h2>{{ $auction->title }}</h2>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>{{ __('Sold for') }}: €{{ number_format($auction->current_price, 2) }}</p>
                        <p>{{ __('Sold to') }}: {{ $auction->winner->fullname }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">
                            {{ __('View Auction') }}
                        </a>

                    </div>
                @endforeach
            </div>
            {{ $soldAuctions->links() }}
        @else
            <p>{{ __('You have no sold auctions.') }}</p>
        @endif
    </div>
@endsection
