@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Search Results for') }} "{{ __($query) }}"</h1>
        @if($auctions->isEmpty())
            <p>{{ __('No auctions found.') }}</p>
        @else
            <div class="list-group">
                @foreach($auctions as $auction)
                    <a href="{{ route('auctions.show', $auction->auction_id) }}" class="list-group-item list-group-item-action">
                        <h5 class="mb-1">{{ __($auction->title) }}</h5>
                        <p class="mb-1">{{ __($auction->description) }}</p>
                        <small>{{ __('Category') }}: {{ __($auction->category->name) }}</small>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
