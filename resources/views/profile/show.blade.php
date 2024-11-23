@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Perfil de Usuário') }}</h1>
        <div class="card">
            <div class="card-header">
                {{ $user->username }}
            </div>
            <div class="card-body">
                <p><strong>{{ __('Nome Completo') }}:</strong> {{ $user->fullname }}</p>
                <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                <p><strong>{{ __('NIF') }}:</strong> {{ $user->nif }}</p>
                <!-- Adicione outros campos necessários -->
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">{{ __('Editar Perfil') }}</a>
    </div>
@endsection
