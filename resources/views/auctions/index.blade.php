@extends('layouts.app')

@section('content')
    <div class="container auction-page">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Bloco de informações do leilão (imagem, título, descrição, status...) --}}
        <div class="auction-meta-container" style="margin-top:1rem; margin-bottom:1rem; padding:1rem; border:1px solid #ddd; border-radius:8px;">
            <img src="{{ asset('images/auctions/' . ($auction->image ?? 'default.png')) }}" alt="{{ $auction->title }}" class="auction-image">
            <h2 class="auction-title">{{ $auction->title }}</h2>
            <p class="auction-description">{{ $auction->description }}</p>
            <p class="status">{{ __($auction->status) }}</p>

            <p class="auction-meta">
                <strong>{{ __('Ends on') }}:</strong>
                {{ $auction->ending_date->format('d/m/Y H:i') }}
            </p>
            <p class="auction-meta">
                <strong>{{ __('Seller') }}:</strong>
                <a href="{{ route('user.show', $auction->seller->user_id) }}">
                    {{ $auction->seller->fullname }}
                </a>
            </p>
            <p class="auction-meta">
                <strong>{{ __('Current Bid') }}:</strong>
                €{{ number_format($auction->current_price, 2, ',', '.') }}
            </p>
            <p class="auction-meta">
                <strong>{{ __('Minimum Bid Increment') }}:</strong>
                €{{ number_format($auction->minimum_bid_increment, 2, ',', '.') }}
            </p>

            {{-- Timer (opcional, caso queira exibir contagem regressiva) --}}
            <p>{{ __('Time Remaining') }}:
                <span class="auction-timer" data-end-time="{{ $auction->ending_date->toIso8601String() }}"></span>
            </p>
        </div>

        @auth
            {{-- Se o usuário for o dono do leilão --}}
            @if (auth()->user()->user_id === $auction->user_id)
                <div class="owner-actions">
                    <a href="{{ route('auctions.edit', $auction) }}" class="btn btn-primary">
                        {{ __('Edit Auction') }}
                    </a>

                    {{-- Encerrar agora (sem escolher lances) --}}
                    <form action="{{ route('auctions.endManually', $auction) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            {{ __('End Auction Now') }}
                        </button>
                    </form>

                    {{-- Cancel Auction --}}
                    <form action="{{ route('auctions.cancel', $auction) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-dark"
                                onclick="return confirm('{{ __('Are you sure you want to cancel this auction?') }}');">
                            {{ __('Cancel Auction') }}
                        </button>
                    </form>

                    {{-- Deletar do BD --}}
                    <form action="{{ route('auctions.destroy', $auction->auction_id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('{{ __('Are you sure you want to delete this auction?') }}');">
                            {{ __('Delete Auction') }}
                        </button>
                    </form>
                </div>

                {{-- Caso seja um Administrador (e não o dono) --}}
            @elseif (auth()->user()->is_admin)
                {{-- Se o leilão não estiver suspenso, exibir botão de suspender --}}
                @if($auction->status !== 'suspended')
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendAuctionModal">
                        {{ __('Suspend Auction') }}
                    </button>
                @endif

                {{-- Se o leilão estiver suspenso, exibir botão de reativar --}}
                @if($auction->status === 'suspended')
                    <form action="{{ route('admin.auctions.unsuspend', $auction->auction_id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            {{ __("Reactivate Auction") }}
                        </button>
                    </form>
                @endif

                {{-- Caso seja um usuário normal (não dono, não admin) --}}
            @else
                <form action="{{ route('bids.store', $auction) }}" method="POST" class="bid-form">
                    @csrf
                    <div class="form-group">
                        <label for="price">{{ __('Bid Price') }} (€):</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" required
                               placeholder="{{ __('Enter your bid') }}">
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('Place Bid') }}</button>
                </form>
            @endif

            {{-- Botão "Contact Seller" se não for o próprio dono --}}
            @if (auth()->user()->user_id !== $auction->user_id)
                <form action="{{ route('messages.start') }}" method="POST" style="display:inline-block; margin-left:1em;">
                    @csrf
                    <input type="hidden" name="auction_id" value="{{ $auction->auction_id }}">
                    <button type="submit" class="btn btn-secondary">
                        {{ __('Contact Seller') }}
                    </button>
                </form>
            @endif

            {{-- Seguir / Deixar de seguir --}}
            @if($isFollowed)
                <form action="{{ route('auction.unfollow', ['auction_id' => $auction->auction_id]) }}"
                      method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Unfollow Auction') }}</button>
                </form>
            @else
                <form action="{{ route('auction.follow', ['auction_id' => $auction->auction_id]) }}"
                      method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-primary">{{ __('Follow Auction') }}</button>
                </form>
            @endif
        @endauth

        <a href="{{ route('auctions.biddingHistory', $auction) }}" class="btn btn-info mt-3">
            {{ __('View Bidding History') }}
        </a>
    </div>

    {{-- Modal de suspender leilão (para admin) --}}
    <div class="modal fade" id="suspendAuctionModal" tabindex="-1" aria-labelledby="suspendAuctionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.auctions.suspend', $auction->auction_id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="suspendAuctionModalLabel">{{ __('Suspend Auction') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="suspendReason">{{ __('Reason for Suspension') }}</label>
                            <textarea class="form-control" id="suspendReason" name="reason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-warning">
                            {{ __('Confirm suspension') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timers = document.querySelectorAll('.auction-timer');
            let timerElements = [];

            // Inicializa os timers
            timers.forEach(timer => {
                const endTime = new Date(timer.getAttribute('data-end-time'));
                if (!isNaN(endTime)) {
                    timerElements.push({ element: timer, endTime: endTime });
                }
            });

            function getTimeDifference(end, now) {
                let years = end.getFullYear() - now.getFullYear();
                let months = end.getMonth() - now.getMonth();
                let days = end.getDate() - now.getDate();
                let hours = end.getHours() - now.getHours();
                let minutes = end.getMinutes() - now.getMinutes();
                let seconds = end.getSeconds() - now.getSeconds();

                if (seconds < 0) {
                    seconds += 60;
                    minutes--;
                }
                if (minutes < 0) {
                    minutes += 60;
                    hours--;
                }
                if (hours < 0) {
                    hours += 24;
                    days--;
                }
                if (days < 0) {
                    const previousMonth = new Date(end.getFullYear(), end.getMonth(), 0);
                    days += previousMonth.getDate();
                    months--;
                }
                if (months < 0) {
                    months += 12;
                    years--;
                }

                return { years, months, days, hours, minutes, seconds };
            }

            function updateAllTimers() {
                const now = new Date();
                let activeTimers = [];

                timerElements.forEach(timerObj => {
                    const { endTime, element } = timerObj;
                    if (endTime <= now) {
                        element.textContent = '{{ __("Auction ended") }}';
                    } else {
                        const diff = getTimeDifference(endTime, now);
                        let timeString = '';

                        if (diff.years > 0) {
                            timeString += `${diff.years}{{ __('y') }} `;
                        }
                        if (diff.months > 0) {
                            timeString += `${diff.months}{{ __('m') }} `;
                        }
                        if (diff.days > 0) {
                            timeString += `${diff.days}{{ __('d') }} `;
                        }
                        if (diff.hours > 0) {
                            timeString += `${diff.hours}{{ __('h') }} `;
                        }
                        if (diff.minutes > 0) {
                            timeString += `${diff.minutes}{{ __('m') }} `;
                        }
                        if (diff.seconds > 0) {
                            timeString += `${diff.seconds}{{ __('s') }}`;
                        }

                        element.textContent = timeString.trim();
                        activeTimers.push(timerObj);
                    }
                });

                timerElements = activeTimers;
            }

            // Atualiza os timers a cada segundo
            setInterval(updateAllTimers, 1000);
        });
    </script>
@endpush
