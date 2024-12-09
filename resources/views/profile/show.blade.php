@extends('layouts.app')

@section('content')
<div class="profile-div">
        <h1>{{ __('User Profile') }}</h1>
        <div class="profile-picture-container">
            <img src="{{ route('profile.picture', ['user_id' => $user->user_id]) }}" alt="Profile Picture" class="profile-picture">
        </div>
        <div class="card">
            <div class="card-header">
                {{ $user->username }}
            </div>
            <div class="card-body">
                <p><strong>{{ __('Full Name') }}:</strong> {{ $user->fullname }}</p>
                <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                <p><strong>{{ __('Tax ID') }}:</strong> {{ $user->nif }}</p>
                <!-- Add other necessary fields -->
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">{{ __('Edit Profile') }}</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
        </form>
        <!-- Logout Button -->
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <!-- Current Password -->
            <div class="mb-4" id="hiddenPasswordInput" style="display: none">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Current Password') }}</label>
                <input id="password" type="password" name="password" required placeholder="Enter your password">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" id="deleteButton" class="btn btn-danger" style="background-color: red">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </div>
    @if ($myauctions->count() > 0)
        <section class="my-auctions">
            <div class="container">
                <h1>{{ __('My Auctions') }}</h1>
                <div class="auction-grid">
                    @foreach($myauctions as $auction)
                        <div class="auction-card">
                            <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
                            <h2>{{ $auction->title }}</h2>
                            <p>{{ Str::limit($auction->description, 100) }}</p>
                            <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
                            <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
