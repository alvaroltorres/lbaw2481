@extends('layouts.app')

@section('content')
    <h1>Perfil de {{ $user->fullname }}</h1>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Username:</strong> {{ $user->username }}</p>
    <p><strong>NIF:</strong> {{ $user->nif }}</p>
    <p><strong>Admin:</strong> {{ $user->is_admin ? 'Sim' : 'Não' }}</p>
    <p><strong>Empresa:</strong> {{ $user->is_enterprise ? 'Sim' : 'Não' }}</p>

    <h2>Leilões Criados</h2>
    <div class="auction-grid">
        @forelse($user->auctions as $auction)
            <div class="auction-card">
                <h3>{{ $auction->title }}</h3>
                <p>{{ Str::limit($auction->description, 100) }}</p>
                <p>Current Bid: ${{ number_format($auction->current_price, 2) }}</p>
                <a href="{{ route('admin.users.show', ['user' => $auction->user_id]) }}" class="btn btn-primary">View Seller</a>
            </div>
        @empty
            <p>Este utilizador não tem leilões ativos.</p>
        @endforelse
    </div>
@endsection
