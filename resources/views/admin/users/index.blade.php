@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 text-center">{{ __('Lista de Utilizadores') }}</h1>

        <!-- Formulário de Pesquisa -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form action="{{ route('admin.users.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" placeholder="{{ __('Pesquisar utilizador...') }}"
                       value="{{ old('query', $searchTerm ?? '') }}"
                       class="form-control me-2" style="max-width: 300px;">
                <button type="submit" class="btn btn-primary">{{ __('Pesquisar') }}</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">{{ __('Criar Utilizador') }}</a>
        </div>

        <!-- Tabela de Utilizadores -->
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light text-center">
                <tr>
                    <th class="text-start">{{ __('Nome') }}</th>
                    <th class="text-start">{{ __('Email') }}</th>
                    <th class="text-center">{{ __('Ações') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                               class="btn btn-sm btn-success me-2">{{ __('Editar') }}</a>
                            <button class="btn btn-sm btn-danger delete-user-btn"
                                    data-user-id="{{ $user->user_id }}">
                                {{ __('Apagar') }}
                            </button>

                            @if(!$user->is_admin)
                                @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user->user_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-warning ms-2">{{ __('Desbloquear') }}</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary ms-2 block-user-btn"
                                            data-user-id="{{ $user->user_id }}">
                                        {{ __('Bloquear') }}
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">{{ __('Nenhum utilizador encontrado.') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Bloquear Utilizador -->
    <div class="modal fade" id="blockUserModal" tabindex="-1" aria-labelledby="blockUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="blockUserForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="blockUserModalLabel">{{ __('Bloquear Utilizador') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="blockUserId" value="">
                        <div class="form-group">
                            <label for="reason">{{ __('Razão para o Bloqueio') }}</label>
                            <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Confirmar Bloqueio') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-user-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const userId = this.getAttribute('data-user-id');
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`user-row-${userId}`).remove();
                                alert('Utilizador apagado com sucesso!');
                            } else {
                                alert(data.message || 'Erro ao apagar utilizador.');
                            }
                        })
                        .catch(error => {
                            console.error('Erro na requisição:', error);
                            alert('Ocorreu um erro ao tentar apagar o utilizador.');
                        });
                });
            });
        });
    </script>
@endsection
