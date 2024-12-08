@extends('layouts.app')

@section('content')
    <h1>{{ __('Create New User') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <label for="fullname">{{ __('Full Name') }}:</label>
        <input type="text" name="fullname" id="fullname" class="@error('fullname') is-invalid @enderror" value="{{ old('fullname') }}" required placeholder="Enter full name">
        @error('fullname')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="email">{{ __('Email') }}:</label>
        <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="Enter email address">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="password">{{ __('Password') }}:</label>
        <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror" required placeholder="Enter password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <button type="submit">{{ __('Create') }}</button>
    </form>
@endsection