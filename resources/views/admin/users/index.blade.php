@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 20px;">
        <h1 class="mb-4">{{ __('Users List') }}</h1>

        <!-- Formulário de Pesquisa -->
        <form action="{{ route('admin.users.search') }}" method="GET" class="mb-4" style="display: flex; align-items: center; gap: 10px;">
            <input type="text" name="query" placeholder="{{ __('Search User...') }}"
                   value="{{ old('query', $searchTerm ?? '') }}"
                   class="form-control"
                   style="max-width: 300px; height: 38px; box-sizing: border-box;">
            <button type="submit" class="btn btn-primary"
                    style="height: 38px; box-sizing: border-box;">{{ __('Search') }}</button>
        </form>



        <!-- Botão Criar Utilizador -->
        <div class="mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">{{ __('Create User') }}</a>
        </div>

        <!-- Tabela de Utilizadores -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light text-center">
                <tr>
                    <th style="text-align: left;">{{ __('Name') }}</th>
                    <th style="text-align: left;">{{ __('Email') }}</th>
                    <th class="text-center">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr class="align-middle" id="user-row-{{ $user->user_id }}">
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                               class="btn btn-sm btn-success me-2">{{ __('Edit') }}</a>
                            <button class="btn btn-sm btn-danger delete-user-btn"
                                    data-user-id="{{ $user->user_id }}">
                                {{ __('Delete') }}
                            </button>

                            @if(!$user->is_admin)
                                @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user->user_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-sm btn-warning ms-2">{{ __('Unblock') }}</button>
                                    </form>
                                @else
                                    <!-- Botão para abrir o modal -->
                                    <button class="btn btn-sm btn-secondary ms-2 block-user-btn"
                                            data-user-id="{{ $user->user_id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#blockUserModal">
                                        {{ __('Block') }}
                                    </button>
                                @endif
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">{{ __('No Users blocked') }}</td>
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
                <form method="POST" id="blockUserForm" >
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="blockUserModalLabel">{{ __('Block User') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="blockReason">{{ __('Reason for the Block') }}</label>
                            <textarea class="form-control" id="blockReason" name="reason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Confirm Block') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Esperar que o DOM esteja totalmente carregado
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Script carregado com sucesso.');

            const deleteButtons = document.querySelectorAll('.delete-user-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const userId = this.getAttribute('data-user-id');
                    console.log('A tentar apagar utilizador com ID:', userId);

                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    })
                        .then(response => {
                            console.log('Resposta HTTP:', response.status);
                            return response.json(); // Alterado para JSON diretamente
                        })
                        .then(data => {
                            console.log('Resposta do servidor:', data);

                            if (data.success) {
                                const userRow = document.getElementById(`user-row-${userId}`);
                                if (userRow) {
                                    userRow.remove();
                                    console.log('User successfully deleted.');
                                }
                                alert('User successfully deleted.');
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


        document.addEventListener('DOMContentLoaded', function () {
            const blockButtons = document.querySelectorAll('.block-user-btn');
            const blockForm = document.getElementById('blockUserForm');

            blockButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.getAttribute('data-user-id');
                    console.log('Botão Block clicado, ID do utilizador:', userId);
                    blockForm.action = `/admin/users/${userId}/block`;
                });
            });
        });

    </script>
@endsection
