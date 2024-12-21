@extends('layouts.app')

@section('content')
    <div class="notifications-page">
        <h1>{{ __('My Notifications') }}</h1>
        @if($notifications->count() > 0)
            <ul class="notifications-list">
                @foreach($notifications as $notification)
                    <li class="notification-item @if(is_null($notification->read_at)) unread @endif">
                        <a href="{{ route('notifications.show', $notification->id) }}">
                            {{ $notification->data['message'] ?? __('New notification') }}
                            <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>{{ __('You have no notifications.') }}</p>
        @endif
    </div>
@endsection
