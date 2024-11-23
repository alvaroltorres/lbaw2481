@extends('layouts.app')

@section('content')
    <h1>Editar Utilizador</h1>

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

        <label for="fullname">Nome Completo:</label>
        <input type="text" name="fullname" id="fullname" value="{{ old('fullname', $user->fullname) }}" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>

        <label for="is_admin">É administrador?</label>
        <select name="is_admin" id="is_admin" required>
            <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>Não</option>
        </select>

        <label for="is_enterprise">É empresa?</label>
        <select name="is_enterprise" id="is_enterprise" required>
            <option value="1" {{ $user->is_enterprise ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ !$user->is_enterprise ? 'selected' : '' }}>Não</option>
        </select>

        <label for="nif">NIF:</label>
        <input type="text" name="nif" id="nif" value="{{ old('nif', $user->nif) }}" required>

        <button type="submit">Salvar Alterações</button>
    </form>
@endsection
