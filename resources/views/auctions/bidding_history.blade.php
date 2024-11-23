@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bidding History for {{ $auction->title }}</h1>

        @if($bids->isEmpty())
            <p>No bids have been placed on this auction yet.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>Bidder</th>
                    <th>Bid Amount</th>
                    <th>Bid Time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bids as $bid)
                    <tr>
                        <td>{{ $bid->user->username }}</td>
                        <td>${{ number_format($bid->price, 2) }}</td>
                        <td>{{ $bid->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <!-- Back to Auction Button -->
        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-secondary">Back to Auction</a>
    </div>
@endsection
