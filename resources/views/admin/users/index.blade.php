@extends('layouts.app')

@section('content')
    <h1>{{ __('User List') }}</h1>
    <table>
        <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->user_id) }}">{{ __('Edit') }}</a>
                    <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">{{ __('Delete') }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
