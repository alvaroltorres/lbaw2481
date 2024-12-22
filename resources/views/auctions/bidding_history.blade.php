@extends('layouts.app')

@section('content')
    <div class="container my-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ __('Histórico de Lances para') }} {{ $auction->title }}</h2>
                </div>
                @if($bids->isNotEmpty())
                    <div class="mt-2">
                        <strong>{{ __('Maior Lance:') }}</strong>
                        <span class="text-primary">${{ number_format($bids->max('price'), 2) }}</span>
                    </div>
                @endif
            <div class="card-body p-4">
                @if($bids->isEmpty())
                    <div class="alert alert-info text-center" role="alert">
                        {{ __('Nenhum lance foi realizado neste leilão ainda.') }}
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">{{ __('Licitação') }}</th>
                                <th scope="col">{{ __('Valor do Lance') }}</th>
                                <th scope="col">{{ __('Hora do Lance') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bids as $bid)
                                <tr @if($bid->price == $bids->max('price')) class="table-warning" @endif>
                                    <td>
                                        <a href="{{ route('user.show', $bid->user) }}" class="text-decoration-none text-dark">
                                            {{ $bid->user->username }}
                                        </a>
                                    </td>
                                    <td>
                                        ${{ number_format($bid->price, 2) }}
                                    </td>
                                    <td>
                                        {{ $bid->time->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="{{ route('auctions.show', $auction) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> {{ __('Voltar para o Leilão') }}
                </a>
            </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Estilização do Cabeçalho */
        .card-header h2 {
            font-size: 1.5rem;
            color: #333;
        }

        .badge-success {
            font-size: 0.9rem;
        }

        /* Estilização dos Links */
        a.text-decoration-none:hover {
            text-decoration: underline;
        }

        /* Destaque da Linha com o Maior Lance */
        .table-warning {
            background-color: #fff3cd !important;
        }

        /* Responsividade da Tabela */
        @media (max-width: 576px) {
            .card-header h2 {
                font-size: 1.2rem;
            }

            .badge-success {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Inclusão do Font Awesome para ícones -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Script para Contagem Regressiva do Tempo Restante -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const timeRemainingElement = document.getElementById('timeRemaining');
            const auctionEndTime = new Date("{{ $auction->end_time }}").getTime();

            const countdown = setInterval(() => {
                const now = new Date().getTime();
                const distance = auctionEndTime - now;

                if (distance < 0) {
                    clearInterval(countdown);
                    timeRemainingElement.innerText = "{{ __('Leilão Encerrado') }}";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let timeString = '';
                if (days > 0) timeString += days + 'd ';
                if (hours > 0) timeString += hours + 'h ';
                if (minutes > 0) timeString += minutes + 'm ';
                timeString += seconds + 's';

                timeRemainingElement.innerText = timeString;
            }, 1000);
        });
    </script>
@endpush
