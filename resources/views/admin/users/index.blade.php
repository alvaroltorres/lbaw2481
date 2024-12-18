@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 20px;">
        <h1 class="mb-4">{{ __('Lista de Utilizadores') }}</h1>

        <!-- Formulário de Pesquisa -->
        <form action="{{ route('admin.users.search') }}" method="GET" class="mb-4 d-flex align-items-center">
            <input type="text" name="query" placeholder="{{ __('Pesquisar utilizador...') }}"
                   value="{{ old('query', $searchTerm ?? '') }}"
                   class="form-control me-2" style="max-width: 300px;">
            <button type="submit" class="btn btn-primary">{{ __('Pesquisar') }}</button>
        </form>

        <!-- Botão Criar Utilizador -->
        <div class="mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">{{ __('Criar Utilizador') }}</a>
        </div>

        <!-- Tabela de Utilizadores -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light text-center">
                <tr>
                    <th style="text-align: left;">{{ __('Nome') }}</th>
                    <th style="text-align: left;">{{ __('Email') }}</th>
                    <th class="text-center">{{ __('Ações') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr class="align-middle" id="user-row-{{ $user->user_id }}">
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                               class="btn btn-sm btn-success me-2">{{ __('Editar') }}</a>
                            <button class="btn btn-sm btn-danger delete-user-btn"
                                    data-user-id="{{ $user->user_id }}">
                                {{ __('Apagar') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">{{ __('Nenhum utilizador encontrado.') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
