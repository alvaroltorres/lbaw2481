
@extends('layouts.app')

@section('content')
    <h1>{{ __('Profile of') }} {{ $user->fullname }}</h1>
    <p><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
    <p><strong>{{ __('Username:') }}</strong> {{ $user->username }}</p>
    <p><strong>{{ __('Tax ID:') }}</strong> {{ $user->nif }}</p>
    <p><strong>{{ __('Admin:') }}</strong> {{ $user->is_admin ? __('Yes') : __('No') }}</p>
    <p><strong>{{ __('Enterprise:') }}</strong> {{ $user->is_enterprise ? __('Yes') : __('No') }}</p>

    <h2>{{ __('Created Auctions') }}</h2>
    <div class="auction-grid">
        @forelse($user->auctions as $auction)
            <div class="auction-card">
                <h3>{{ $auction->title }}</h3>
                <p>{{ Str::limit($auction->description, 100) }}</p>
                <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
                <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>
            </div>
        @empty
            <p>{{ __('This user has no active auctions.') }}</p>
        @endforelse
    </div>
@endsection
