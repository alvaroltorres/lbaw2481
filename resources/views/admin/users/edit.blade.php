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
        <input type="text" name="fullname" id="fullname" class="@error('fullname') is-invalid @enderror" value="{{ old('fullname', $user->fullname) }}" required placeholder="Digite o nome completo">
        @error('fullname')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="Digite o endereço de email">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" class="@error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required placeholder="Digite o nome de usuário">
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="is_admin">É administrador?</label>
        <select name="is_admin" id="is_admin" class="@error('is_admin') is-invalid @enderror" required>
            <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>Não</option>
        </select>
        @error('is_admin')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="nif">NIF:</label>
        <input type="text" name="nif" id="nif" class="@error('nif') is-invalid @enderror" value="{{ old('nif', $user->nif) }}" required placeholder="Digite o NIF">
        @error('nif')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <button type="submit">Salvar Alterações</button>
    </form>
@endsection
