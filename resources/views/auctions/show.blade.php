@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@section('content')
    <div class="container auction-page">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Bloco de informações do leilão (imagem, título, descrição...) --}}
        <div class="auction-meta-container" style="margin-top:1rem; margin-bottom:1rem; padding:1rem; border:1px solid #ddd; border-radius:8px;">
            @php
                $finalImage = $auction->image
                    ? Storage::url('public/images/auctions/'. $auction->image)
                    : Storage::url('public/images/auctions/default.png');
            @endphp

            <img src="{{ asset($finalImage) }}" alt="{{ $auction->title }}" class="show-auction-image">

            <h2 class="auction-title">{{ $auction->title }}</h2>
            <p class="auction-description">{{ $auction->description }}</p>
            <p class="status">{{ __($auction->status) }}</p>
            <p class="auction-meta">
                <strong>{{ __('Image') }}:</strong>
                {{ $auction->image }}

            {{-- Data de término e vendedor --}}
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

            {{-- Preço atual e incrementos --}}
            <p class="auction-meta">
                <strong>{{ __('Current Bid') }}:</strong>
                €{{ number_format($auction->current_price, 2, ',', '.') }}
            </p>
            <p class="auction-meta">
                <strong>{{ __('Minimum Bid Increment') }}:</strong>
                €{{ number_format($auction->minimum_bid_increment, 2, ',', '.') }}
            </p>

        </div>

        @auth
            {{-- Se o usuário logado for o dono do leilão --}}
            @if (auth()->user()->user_id === $auction->user_id)
                <div class="owner-actions">
                    <a href="{{ route('auctions.edit', $auction) }}" class="btn btn-primary">
                        {{ __('Edit Auction') }}
                    </a>

                    <!-- Encerrar agora (sem escolher lances) -->
                    <form action="{{ route('auctions.endManually', $auction) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            {{ __('End Auction Now') }}
                        </button>
                    </form>


                    <!-- Deletar do BD -->
                    <form action="{{ route('auctions.destroy', $auction) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('{{ __('Are you sure you want to delete this auction?') }}');">
                            {{ __('Delete Auction') }}
                        </button>
                    </form>
                </div>

                {{-- Caso seja um administrador (e não o dono) --}}
            @elseif (auth()->user()->is_admin)
                {{-- Exemplo de botões de suspender/reativar o leilão (caso precise) --}}
                @if($auction->status !== 'suspended')
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendAuctionModal">
                        {{ __('Suspend Auction') }}
                    </button>
                @endif

                @if($auction->status === 'suspended')
                    <form action="{{ route('admin.auctions.unsuspend', $auction) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ __("Reactivate Auction") }}</button>
                    </form>
                @endif
                @endif

                <form action="{{ route('bids.store', $auction) }}" method="POST" class="bid-form">
                    @csrf
                    <div class="form-group">
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    @if($auction->status !== 'Active')
                        <div class="alert alert-danger">{{ __('This auction is not active.') }}</div>
                    @else
                        <label for="price" class="me-2">{{ __('Bid Price') }} (€):</label>
                            <input type="number" name="price" id="price" class="form-control me-2" step="0.01" required
                                   placeholder="{{ __('Enter your bid') }}">
                            <button type="submit" class="btn btn-primary" style="white-space: nowrap; margin-top: 1em; margin-bottom: 1em;">
                                {{ __('Place Bid') }}
                    @endif
                </form>

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

            {{-- Botão de seguir/desseguir o leilão --}}
            @if($isFollowed && !auth()->user()->is_admin)
                <form action="{{ route('auction.unfollow', ['auction_id' => $auction->auction_id]) }}"
                      method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Unfollow Auction') }}</button>
                </form>
                @elseif(!$isFollowed && !auth()->user()->is_admin)
                <form action="{{ route('auction.follow', ['auction_id' => $auction->auction_id]) }}"
                      method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-primary">{{ __('Follow Auction') }}</button>
                </form>

            @endif
        @endauth
        <form action="{{ route('auctions.biddingHistory', $auction) }}" method="GET" style="display:inline-block;">
            <button type="submit" class="btn btn-secondary">{{ __('View Bidding History') }}</button>
        </form>

    </div>

    {{-- Modal para suspender o leilão (apenas para admin, se desejar) --}}
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ __('Confirm suspension') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
