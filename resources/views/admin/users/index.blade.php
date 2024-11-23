@extends('layouts.app')

@section('content')
    <h1>Lista de Utilizadores</h1>
    <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->user_id) }}">Editar</a>
                    <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Apagar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
