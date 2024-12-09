@extends('layouts.app')

@section('content')
    <div class="profile-div">
        <h1>{{ __('Edit Profile') }}</h1>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">
                {{ __('Profile updated successfully.') }}
            </div>
        @endif

        <div class="profile-picture-container">
            <img src="{{ route('profile.picture', ['user_id' => $user->user_id]) }}" alt="Profile Picture" class="profile-picture">
        </div>

        <!-- Profile Picture Upload Form -->
        <form action="{{ route('profile.picture.store') }}" method="POST" enctype="multipart/form-data" class="card mb-4">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="profile_picture">{{ __('Upload Profile Picture:') }}</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.update') }}" class="card">
            @csrf
            @method('PATCH')

            <div class="card-body">
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
            </div>
        </form>
    </div>
@endsection