@extends('layouts.app')

@section('content')
    <h1>{{ __('Lista de Utilizadores') }}</h1>
    <table id="users-table">
        <thead>
        <tr>
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
                    <button class="delete-user-btn" data-user-id="{{ $user->user_id }}">{{ __('Apagar') }}</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
                        fetch(`{{ url('/admin/user') }}/${userId}`, {
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
