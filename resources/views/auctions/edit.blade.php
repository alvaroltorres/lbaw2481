@extends('layouts.app')

@section('content')
    <h1>{{ __('Edit User') }}</h1>

    <!-- Exibir Erros de Validação -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
        @csrf
        @method('PATCH')

        <!-- Nome Completo -->
        <label for="fullname">{{ __('Full Name') }}:</label>
        <input type="text" name="fullname" id="fullname" value="{{ old('fullname', $user->fullname) }}" required>

        <!-- Email -->
        <label for="email">{{ __('Email') }}:</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>

        <!-- Username -->
        <label for="username">{{ __('Username') }}:</label>
        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>

        <!-- É administrador -->
        <label for="is_admin">{{ __('Is Admin?') }}</label>
        <select name="is_admin" id="is_admin" required>
            <option value="1" {{ $user->is_admin ? 'selected' : '' }}>{{ __('Yes') }}</option>
            <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>{{ __('No') }}</option>
        </select>

        <!-- É empresa -->
        <label for="is_enterprise">{{ __('Is Enterprise?') }}</label>
        <select name="is_enterprise" id="is_enterprise" required>
            <option value="1" {{ $user->is_enterprise ? 'selected' : '' }}>{{ __('Yes') }}</option>
            <option value="0" {{ !$user->is_enterprise ? 'selected' : '' }}>{{ __('No') }}</option>
        </select>

        <!-- NIF -->
        <label for="nif">{{ __('NIF') }}:</label>
        <input type="text" name="nif" id="nif" value="{{ old('nif', $user->nif) }}" required>

        <!-- Botão de salvar -->
        <button type="submit">{{ __('Save Changes') }}</button>
    </form>
@endsection
