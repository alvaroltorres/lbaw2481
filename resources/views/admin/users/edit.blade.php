@extends('layouts.app')

@section('content')
    <h1>{{ __('Edit User') }}</h1>
    <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
        @csrf
        @method('PATCH')
        <label for="fullname">{{ __('Full Name') }}:</label>
        <input type="text" name="fullname" id="fullname" value="{{ $user->fullname }}" required>

        <label for="email">{{ __('Email') }}:</label>
        <input type="email" name="email" id="email" value="{{ $user->email }}" required>

        <button type="submit">{{ __('Save Changes') }}</button>
    </form>
@endsection
