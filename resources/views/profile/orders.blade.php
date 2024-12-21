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
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>
                            <a href="{{ route('auctions.show', $order->transaction->auction->auction_id) }}">
                                {{ $order->transaction->auction->title }}
                            </a>
                        </td>
                        <td>€{{ number_format($order->transaction->value, 2, ',', '.') }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ __($order->transaction->status) }}</td>
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
