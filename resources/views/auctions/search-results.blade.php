@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results for "{{ $query }}"</h1>
    @if($auctions->isEmpty())
        <p>No auctions found.</p>
    @else
        <div class="list-group">
            @foreach($auctions as $auction)
                <a href="{{ route('auctions.show', $auction->auction_id) }}" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">{{ $auction->title }}</h5>
                    <p class="mb-1">{{ $auction->description }}</p>
                    <small>Category: {{ $auction->category->name }}</small>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection