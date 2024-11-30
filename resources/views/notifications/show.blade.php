@extends('layouts.app')

@section('content')

    <h1>{{ __('Notification Details') }}</h1>

    <p>{{ $notification->data['message'] ?? $notification->content }}</p>

    <p>
        @if($notification->bid)
            <strong>{{ __('Bid Amount') }}:</strong> €{{ number_format($notification->bid->price, 2, ',', '.') }}<br>
            <strong>{{ __('Bidder') }}:</strong>
            <a href="{{ route('user.show', $notification->bid->user_id) }}">
                {{ \App\Models\User::find($notification->bid->user_id)->fullname}}
            </a><br>
        @endif
        @if($notification->auction)
            <strong>{{ __('Auction') }}:</strong>
            <a href="{{ route('auctions.show', $notification->auction->auction_id) }}">
                {{ $notification->auction->title }}
            </a>
        @endif
    </p>

    <p><small>{{ $notification->created_at->format('d/m/Y H:i:s') }}</small></p>

@endsection
