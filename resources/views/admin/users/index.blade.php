@extends('layouts.app')

@section('content')
    <h1>{{ __('Lista de Utilizadores') }}</h1>

    <!-- Formulário de Pesquisa -->
    <form action="{{ route('admin.users.search') }}" method="GET" class="mb-4">
        <input type="text" name="query" placeholder="{{ __('Pesquisar utilizador...') }}"
               value="{{ old('query', $searchTerm ?? '') }}"
               style="padding: 8px; margin-bottom: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit"
                style="padding: 8px 12px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            {{ __('Pesquisar') }}
        </button>
    </form>

    <a href="{{ route('admin.users.create') }}"
       style="display: inline-block; padding: 8px 12px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 4px; margin-bottom: 10px;">
        {{ __('Criar Utilizador') }}
    </a>


    <!-- Tabela de Utilizadores -->
    <table id="users-table" style="width: 100%; border-collapse: collapse;">
        <thead>
        <tr style="background-color: #f4f4f4;">
            <th>{{ __('Nome') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Ações') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr id="user-row-{{ $user->user_id }}">
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->user_id) }}">{{ __('Editar') }}</a>
                    <button class="delete-user-btn" data-user-id="{{ $user->user_id }}"
                            style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                        {{ __('Apagar') }}
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if ($users->isEmpty())
        <p>{{ __('Nenhum utilizador encontrado.') }}</p>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Selecionar todos os botões de deletar
            const deleteButtons = document.querySelectorAll('.delete-user-btn');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const userId = this.getAttribute('data-user-id');
                    const row = document.getElementById(`user-row-${userId}`);

                    if (confirm('{{ __("Tem certeza que deseja apagar este utilizador?") }}')) {
                        fetch(`{{ url('/admin/users') }}/${userId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                }
                                throw new Error('Erro na resposta da rede.');
                            })
                            .then(data => {
                                if (data.success) {
                                    // Remover a linha da tabela
                                    row.remove();
                                    alert('{{ __("Utilizador apagado com sucesso.") }}');
                                } else {
                                    alert('{{ __("Falha ao apagar o utilizador.") }}');
                                }
                            })
                            .catch(error => {
                                console.error('Ocorreu um problema com a operação fetch:', error);
                                alert('{{ __("Ocorreu um erro ao apagar o utilizador.") }}');
                            });
                    }
                });
            });
        });
    </script>
@endsection
