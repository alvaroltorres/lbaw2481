@extends('layouts.app')

@section('content')
    <h1>{{ __('Create New User') }}</h1>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <label for="fullname">{{ __('Full Name') }}:</label>
        <input type="text" name="fullname" id="fullname" required>

        <label for="email">{{ __('Email') }}:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">{{ __('Password') }}:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">{{ __('Create') }}</button>
    </form>
@endsection
