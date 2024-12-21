@extends('layouts.app')

@section('content')
    <div class="notifications-page container">
        <h1>{{ __('My Notifications') }}</h1>

        @if($notifications->count() > 0)
            <ul class="notifications-list">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $message = $data['message'] ?? __('(No message)');
                        $auctionTitle = $data['auction_title'] ?? null;
                        $created = $notification->created_at->diffForHumans();
                    @endphp

                    <li class="notification-item @if(is_null($notification->read_at)) unread @endif">
                        <a href="{{ route('notifications.show', $notification->id) }}">
                            <div>
                                <strong>{{ $message }}</strong>
                                @if($auctionTitle)
                                    <div class="text-muted">
                                        {{ __('Auction:') }} {{ $auctionTitle }}
                                    </div>
                                @endif
                                <div class="notification-time text-muted">
                                    {{ $created }}
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

            {{ $notifications->links() }}
        @else
            <p>{{ __('You have no notifications.') }}</p>
        @endif
    </div>
@endsection



