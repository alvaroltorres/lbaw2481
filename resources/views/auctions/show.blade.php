@extends('layouts.app')

@section('content')
    <div class="container auction-page">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="auction-card">
            <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}" class="auction-image">
            <h2 class="auction-title">{{ $auction->title }}</h2>
            <p class="auction-description">{{ $auction->description }}</p>
            <p class="status">{{ __($auction->status) }}</p>

            <p class="auction-meta">
                <strong>{{ __('Current Bid') }}:</strong>
                €{{ number_format($auction->current_price, 2, ',', '.') }}
            </p>
            <p class="auction-meta">
                <strong>{{ __('Minimum Bid Increment') }}:</strong>
                €{{ number_format($auction->minimum_bid_increment, 2, ',', '.') }}
            </p>


            @auth
                @if (auth()->user()->user_id === $auction->user_id)
                    {{-- Dono do leilão --}}
                    <div class="owner-actions">
                        <a href="{{ route('auctions.edit', $auction) }}" class="btn btn-primary">
                            {{ __('Edit Auction') }}
                        </a>
                        <form action="{{ route('auctions.destroy', $auction) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('{{ __('Are you sure you want to delete this auction?') }}');">
                                {{ __('Delete Auction') }}
                            </button>
                        </form>
                    </div>

                @elseif (auth()->user()->is_admin)
                    {{-- Admin: botões de Cancelar, Suspender, Reativar --}}

                    <!-- SUSPENDER -->
                    @if($auction->status !== 'suspended')
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendAuctionModal">
                            {{ __('Suspend Auction') }}
                        </button>
                    @endif

                    <!-- REATIVAR -->
                    @if($auction->status === 'suspended')
                        <form action="{{ route('admin.auctions.unsuspend', $auction) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">{{__("Reactivate Auction")}}</button>
                        </form>
                    @endif

                @else
                    {{-- Utilizador normal (não dono, não admin) --}}
                    <form action="{{ route('bids.store', $auction) }}" method="POST" class="bid-form">
                        @csrf
                        <div class="form-group">
                            <label for="price">{{ __('Bid Price') }} (€):</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" required placeholder="{{ __('Enter your bid') }}">
                            @error('price')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('Place Bid') }}</button>
                    </form>
                @endif

                {{-- BOTÃO "Contact Seller" (apenas se o utilizador não for o vendedor) --}}
                @if (auth()->user()->user_id !== $auction->user_id)
                    <form action="{{ route('messages.start') }}" method="POST" style="display:inline-block; margin-left:1em;">
                        @csrf
                        <input type="hidden" name="auction_id" value="{{ $auction->auction_id }}">
                        <button type="submit" class="btn btn-secondary">
                            {{ __('Contact Seller') }}
                        </button>
                    </form>
                @endif

                @if($isFollowed)
                    <form action="{{ route('auction.unfollow', ['auction_id' => $auction->auction_id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Unfollow Auction') }}</button>
                    </form>
                @else
                    <form action="{{ route('auction.follow', ['auction_id' => $auction->auction_id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-primary">{{ __('Follow Auction') }}</button>
                    </form>
                @endif
            @endauth

            <a href="{{ route('auctions.biddingHistory', $auction) }}" class="btn btn-info mt-3">
                {{ __('View Bidding History') }}
            </a>
        </div>
    </div>

    {{-- Modal para Suspender Leilão --}}
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
