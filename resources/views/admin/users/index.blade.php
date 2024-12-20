@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 20px;">
        <h1 class="mb-4">{{ __('Lista de Utilizadores') }}</h1>

        <!-- Formulário de Pesquisa -->
        <form action="{{ route('admin.users.search') }}" method="GET" class="mb-4" style="display: flex; align-items: center; gap: 10px;">
            <input type="text" name="query" placeholder="{{ __('Pesquisar utilizador...') }}"
                   value="{{ old('query', $searchTerm ?? '') }}"
                   class="form-control"
                   style="max-width: 300px; height: 38px; box-sizing: border-box;">
            <button type="submit" class="btn btn-primary"
                    style="height: 38px; box-sizing: border-box;">{{ __('Pesquisar') }}</button>
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

                            @if(!$user->is_admin)
                                @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user->user_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-sm btn-warning ms-2">{{ __('Desbloquear') }}</button>
                                    </form>
                                @else
                                    <!-- Botão para abrir o modal -->
                                    <button class="btn btn-sm btn-secondary ms-2 block-user-btn"
                                            data-user-id="{{ $user->user_id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#blockUserModal">
                                        {{ __('Bloquear') }}
                                    </button>
                                @endif
                            @endif

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
        // Esperar que o DOM esteja totalmente carregado
        document.addEventListener('DOMContentLoaded', function (message) {
            console.log('Script carregado com sucesso.');

            // Seleciona todos os botões de "Apagar"
            const deleteButtons = document.querySelectorAll('.delete-user-btn');

            // Adiciona o evento de clique em cada botão
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); // Impede o comportamento padrão do botão

                    const userId = this.getAttribute('data-user-id'); // Obtém o ID do utilizador

                    // Faz a requisição AJAX para apagar o utilizador
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            console.log('Status HTTP:', response.status); // Verifica o status da resposta
                            return response.text(); // Captura a resposta como texto para depurar
                        })
                        .then(data => {
                            console.log('Resposta bruta do servidor:', data);
                            try {
                                const json = JSON.parse(data); // Converte para JSON
                                if (json.success) {
                                    const userRow = document.getElementById(`user-row-${userId}`);
                                    if (userRow) userRow.remove();
                                    alert('Utilizador apagado com sucesso!');
                                } else {
                                    alert(json.message || 'Erro ao apagar utilizador.');
                                }
                            } catch (error) {
                                console.error('Erro ao processar JSON:', error);
                                alert('Resposta inválida do servidor.', error);
                            }
                        })
                        .catch(error => {
                            console.error('Erro na requisição:', error);
                            alert('Ocorreu um erro ao tentar apagar o utilizador.');
                        });

                }) })
        });
    </script>
@endsection
