@extends('layouts.app')

@section('content')
    <h1>{{ __('My Notifications') }}</h1>
    <ul>
        @foreach($notifications as $notification)
            <li @if(is_null($notification->read_at)) style="font-weight: bold;" @endif>
                <a href="{{ route('notifications.show', $notification->id) }}">
                    {{ $notification->data['message'] ?? __('New notification') }}
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </a>
            </li>
        @endforeach
    </ul>
@endsection
