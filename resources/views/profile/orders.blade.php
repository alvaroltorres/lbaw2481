@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('My Orders') }}</h1>

        @if($orders->count() > 0)
            <table class="table mt-4">
                <thead>
                <tr>
                    <th>{{ __('Order ID') }}</th>
                    <th>{{ __('Auction') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th> <!-- Nova coluna -->
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    @php
                        $transaction = $order->transaction;
                        $auction     = $transaction->auction ?? null;
                    @endphp
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>
                            @if($auction)
                                <a href="{{ route('auctions.show', $auction->auction_id) }}">
                                    {{ $auction->title }}
                                </a>
                            @else
                                <em>{{ __('Auction not found') }}</em>
                            @endif
                        </td>
                        <td>€{{ number_format($transaction->value, 2, ',', '.') }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ __($transaction->status) }}</td>
                        <td>
                            @if($auction && $auction->status === 'Sold')
                                @php
                                    // Verifica se já existe rating
                                    $existingRating = \App\Models\Rating::where('transaction_id', $transaction->transaction_id)->first();
                                @endphp
                                @if(!$existingRating)
                                    <!-- Se não existe rating, mostra o botão -->
                                    <a href="{{ route('ratings.create', $transaction->transaction_id) }}" class="btn btn-sm btn-primary">
                                        {{ __('Rate Seller') }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('Already rated') }}</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $orders->links() }}
        @else
            <p>{{ __('You have no orders.') }}</p>
        @endif
    </div>
@endsection
