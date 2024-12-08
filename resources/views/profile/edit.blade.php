@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Edit Profile') }}</h1>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">
                {{ __('Profile updated successfully.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <!-- Username -->
            <div class="form-group">
                <label for="username">{{ __('Username') }}</label>
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required autofocus placeholder="Enter your username">

                @error('username')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Full Name -->
            <div class="form-group">
                <label for="fullname">{{ __('Full Name') }}</label>
                <input id="fullname" type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname', $user->fullname) }}" required placeholder="Enter your full name">

                @error('fullname')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required placeholder="Enter your email address">

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Tax ID -->
            <div class="form-group">
                <label for="nif">{{ __('Tax ID') }}</label>
                <input id="nif" type="text" class="form-control @error('nif') is-invalid @enderror" name="nif" value="{{ old('nif', $user->nif) }}" required placeholder="Enter your tax ID">

                @error('nif')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Other Fields (if necessary) -->

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
@endsection
