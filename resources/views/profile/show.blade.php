@extends('layouts.app')

@section('content')
    <div class="profile-div">
        <h1>{{ __('Perfil de Usuário') }}</h1>
        <div class="card">
            <div class="card-header">
                {{ $user->username }}
            </div>
            <div class="card-body">
                <p><strong>{{ __('Nome Completo') }}:</strong> {{ $user->fullname }}</p>
                <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                <p><strong>{{ __('NIF') }}:</strong> {{ $user->nif }}</p>
                <!-- Adicione outros campos necessários -->
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">{{ __('Editar Perfil') }}</a>
    </div>
    @if ($myauctions->count() > 0)
    <section class="my-auctions">
        <div class="container">
            <h1>My Auctions</h1>
            <div class="auction-grid">
                @foreach($myauctions as $auction)
                    <div class="auction-card">
                        <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
                        <h2>{{ $auction->title }}</h2>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>Current Bid: ${{ number_format($auction->current_price, 2) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">View Auction</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
