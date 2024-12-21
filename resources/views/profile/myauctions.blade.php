@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('My Auctions') }}</h1>

        @if($myauctions->count() > 0)
            <div class="auction-grid" style="margin-top:1rem; gap:1rem; display:flex; flex-wrap:wrap;">
                @foreach($myauctions as $auction)
                    <div class="auction-card" style="border:1px solid #ddd; padding:1rem; border-radius:8px; width:250px;">
                        <img src="{{ asset('images/auctions/' . ($auction->image ?? 'default.png')) }}" alt="{{ $auction->title }}" style="width:100%; height:150px; object-fit:cover; border-radius:4px;">
                        <h2>{{ $auction->title }}</h2>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>{{ __('Current Bid') }}: €{{ number_format($auction->current_price, 2) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>
                    </div>
                @endforeach
            </div>
            {{ $myauctions->links() }}
        @else
            <p>{{ __('You have no created auctions.') }}</p>
        @endif
    </div>
@endsection
