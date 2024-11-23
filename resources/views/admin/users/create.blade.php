@extends('layouts.app')

@section('content')
    <h1>Criar Novo Utilizador</h1>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <label for="fullname">Nome Completo:</label>
        <input type="text" name="fullname" id="fullname" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Criar</button>
    </form>
@endsection
