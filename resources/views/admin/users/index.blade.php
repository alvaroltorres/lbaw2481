@extends('layouts.app')

@section('content')
    <div class="container" style="padding-left: 20px; padding-right: 20px;">
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
            <table class="table table-striped table-hover" style="margin-left: auto; margin-right: auto;">
                <thead class="table-light text-center">
                <tr>
                    <th style="padding-left: 20px; text-align: left;">{{ __('Nome') }}</th>
                    <th style="padding-left: 20px; text-align: left;">{{ __('Email') }}</th>
                    <th class="text-center">{{ __('Ações') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr class="align-middle">
                        <td style="padding-left: 20px;">{{ $user->fullname }}</td>
                        <td style="padding-left: 20px;">{{ $user->email }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                               class="btn btn-sm btn-success me-2">{{ __('Editar') }}</a>
                            <button class="btn btn-sm btn-danger delete-user-btn"
                                    data-user-id="{{ $user->user_id }}">
                                {{ __('Apagar') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Caso não existam utilizadores -->
        @if ($users->isEmpty())
            <p class="text-center">{{ __('Nenhum utilizador encontrado.') }}</p>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-user-btn');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const userId = this.getAttribute('data-user-id');
                    const row = this.closest('tr');

                    if (confirm('{{ __("Tem certeza que deseja apagar este utilizador?") }}')) {
                        fetch(`{{ url('/admin/users') }}/${userId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    row.remove();
                                    alert('{{ __("Utilizador apagado com sucesso.") }}');
                                } else {
                                    alert('{{ __("Falha ao apagar o utilizador.") }}');
                                }
                            })
                            .catch(error => {
                                console.error('Erro:', error);
                                alert('{{ __("Ocorreu um erro ao apagar o utilizador.") }}');
                            });
                    }
                });
            });
        });
    </script>
@endsection
