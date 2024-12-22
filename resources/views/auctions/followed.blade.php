@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@section('content')

        <div class="container">
        <h1>{{ __('Followed Auctions') }}</h1>

        @if($auctions->isEmpty())
            <p>{{ __('You are not following any auctions.') }}</p>
        @else
            <div class="auction-grid">
                @forelse($auctions as $auction)
                    <div class="auction-card">
                        @php
                            $finalImage = $auction->image
                                ? Storage::url('public/images/auctions/'. $auction->image)
                                : Storage::url('public/images/auctions/default.png');
                        @endphp
                        <img src="{{ asset($finalImage) }}" alt="{{ $auction->title }}" class="auction-image">
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
        </div>
@endsection
