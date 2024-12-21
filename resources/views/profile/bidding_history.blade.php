@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Bidding History for') }} {{ $user->fullname }}</h1>

        @if($bids->isEmpty())
            <p>{{ __('No bids have been placed on this auction yet.') }}</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>{{ __('Bidder') }}</th>
                    <th>{{ __('Bid Amount') }}</th>
                    <th>{{ __('Bid Time') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bids as $bid)
                    <tr>
                        <td>{{ $bid->user->username }}</td>
                        <td>${{ number_format($bid->price, 2) }}</td>
                        <td>{{ $bid->time->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
@endsection
