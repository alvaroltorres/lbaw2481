@extends('layouts.app')

@section('content')
    <h1>{{ __('Criar Novo Utilizador') }}</h1>

    <!-- Formulário completo -->
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <!-- Username -->
        <div>
            <label for="username">{{ __('Username') }}</label>
            <input type="text" name="username" id="username" required>
        </div>

        <!-- Email -->
        <div>
            <label for="email">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" required>
        </div>

        <!-- Nome Completo -->
        <div>
            <label for="fullname">{{ __('Nome Completo') }}</label>
            <input type="text" name="fullname" id="fullname" required>
        </div>

        <!-- Password -->
        <div>
            <label for="password">{{ __('Password') }}</label>
            <input type="password" name="password" id="password" required>
        </div>

        <!-- Confirmar Password -->
        <div>
            <label for="password_confirmation">{{ __('Confirmar Password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <!-- NIF -->
        <div>
            <label for="nif">{{ __('NIF') }}</label>
            <input type="text" name="nif" id="nif">
        </div>

        <!-- É Admin? -->
        <div>
            <label for="is_admin">{{ __('É Admin?') }}</label>
            <select name="is_admin" id="is_admin" required>
                <option value="1">{{ __('Sim') }}</option>
                <option value="0">{{ __('Não') }}</option>
            </select>
        </div>

        <!-- É Empresa? -->
        <div>
            <label for="is_enterprise">{{ __('É Empresa?') }}</label>
            <select name="is_enterprise" id="is_enterprise" required>
                <option value="1">{{ __('Sim') }}</option>
                <option value="0">{{ __('Não') }}</option>
            </select>
        </div>

        <!-- Botão de Submissão -->
        <div style="margin-top: 15px;">
            <button type="submit" style="padding: 8px 12px; background-color: #007BFF; color: white; border: none; border-radius: 4px;">
                {{ __('Guardar Utilizador') }}
            </button>
        </div>
    </form>
@endsection
