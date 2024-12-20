@extends('layouts.app')

@section('content')
    <div class="notification-detail">
        <h1>{{ __('Notification Details') }}</h1>

        <p>{{ $notification->data['message'] ?? $notification->content }}</p>

        @if($notification->bid)
            <p><strong>{{ __('Bid Amount') }}:</strong> €{{ number_format($notification->bid->price, 2, ',', '.') }}</p>
            <p><strong>{{ __('Bidder') }}:</strong>
                <a href="{{ route('user.show', $notification->bid->user_id) }}">
                    {{ optional($notification->bid->user)->fullname ?? __('Unknown User') }}
                </a>
            </p>
        @endif

        @if($notification->auction)
            <p><strong>{{ __('Auction') }}:</strong>
                <a href="{{ route('auctions.show', $notification->auction->auction_id) }}">
                    {{ $notification->auction->title }}
                </a>
            </p>
        @endif

        <p><small>{{ $notification->created_at->format('d/m/Y H:i:s') }}</small></p>
    </div>
@endsection
