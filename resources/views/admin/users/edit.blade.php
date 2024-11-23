@extends('layouts.app')

@section('content')
    <h1>Editar Utilizador</h1>
    <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
        @csrf
        @method('PATCH')
        <label for="fullname">Nome Completo:</label>
        <input type="text" name="fullname" id="fullname" value="{{ $user->fullname }}" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ $user->email }}" required>

        <button type="submit">Salvar Alterações</button>
    </form>
@endsection
