@extends('layouts.app')

@section('content')
    <div class="profile-div container">
        <h1>{{ __('User Profile') }}</h1>

        <div class="profile-picture-container" style="margin-top:1rem;">
            <img src="{{ route('profile.picture', ['user_id' => $user->user_id]) }}" alt="Profile Picture" class="profile-picture">
        </div>
        <div class="card" style="margin-top:1rem;">
            <div class="card-header">
                {{ $user->username }}
            </div>
            <div class="card-body">
                <p><strong>{{ __('Full Name') }}:</strong> {{ $user->fullname }}</p>
                <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                <p><strong>{{ __('Tax ID') }}:</strong> {{ $user->nif }}</p>
            </div>
        </div>

        <div style="margin-top:1rem;">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{ __('Edit Profile') }}</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left:1em;">
                @csrf
                <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
            </form>
        </div>

        <form method="POST" action="{{ route('profile.destroy') }}" style="margin-top:1rem;">
            @csrf
            @method('DELETE')
            <div class="mb-4" id="hiddenPasswordInput" style="display: none">
                <label for="password" class="block text-sm font-medium">{{ __('Current Password') }}</label>
                <input id="password" type="password" name="password" required placeholder="Enter your password" class="form-control">
            </div>
            <button type="submit" id="deleteButton" class="btn btn-danger">
                {{ __('Delete Account') }}
            </button>
        </form>
    </div>
@endsection
