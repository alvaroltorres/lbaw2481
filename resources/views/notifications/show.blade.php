@extends('layouts.app')

@section('content')
    <div class="notification-detail container">
        <h1>{{ __('Notification Details') }}</h1>

        <div class="card p-3">
            @php
                $data = $notification->data;
                $message = $data['message'] ?? __('(No message)');
            @endphp

            <p><strong>{{ __('Message:') }}</strong> {{ $message }}</p>

            @if($notification->auction)
                <p>
                    <strong>{{ __('Auction:') }}</strong>
                    <a href="{{ route('auctions.show', $notification->auction->auction_id) }}">
                        {{ $notification->auction->title }}
                    </a>
                </p>
            @endif

            @if($notification->bid)
                <p><strong>{{ __('Bid Amount:') }}</strong> €{{ number_format($notification->bid->price ?? 0, 2, ',', '.') }}</p>
                <p><strong>{{ __('Bidder:') }}</strong>
                    @if($notification->bid->user)
                        <a href="{{ route('user.show', $notification->bid->user_id) }}">
                            {{ $notification->bid->user->fullname }}
                        </a>
                    @else
                        {{ __('Unknown User') }}
                    @endif
                </p>
            @endif

            <p>
                <strong>{{ __('Created At:') }}</strong>
                {{ $notification->created_at->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
@endsection
