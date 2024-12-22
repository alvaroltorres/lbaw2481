@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 auction-end-page">
        <h1 class="text-3xl font-semibold mb-6">{{ __('End Auction') }}</h1>

        <!-- Detalhes do Leilão -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row">
                <!-- Imagem do Leilão -->
                <div class="lg:w-1/3">
                    @php
                        $finalImage = $auction->image
                            ? Storage::url('public/images/auctions/'. $auction->image)
                            : Storage::url('public/images/auctions/default.png');
                    @endphp
                    <img src="{{ asset($finalImage) }}" alt="{{ $auction->title }}" class="auction-image">
                </div>

                <!-- Informações do Leilão -->
                <div class="lg:w-2/3 lg:pl-6 mt-4 lg:mt-0">
                    <h2 class="text-2xl font-bold mb-2">{{ $auction->title }}</h2>
                    <p class="text-gray-700 mb-4">{{ $auction->description }}</p>
                    <p class="text-gray-600">
                        <strong>{{ __('Status') }}:</strong> {{ __($auction->status) }}
                    </p>
                    <p class="text-gray-600">
                        <strong>{{ __('Current Bid') }}:</strong> €{{ number_format($auction->current_price, 2, ',', '.') }}
                    </p>
                    <p class="text-gray-600">
                        <strong>{{ __('Minimum Bid Increment') }}:</strong> €{{ number_format($auction->minimum_bid_increment, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Lista de Lances -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h3 class="text-2xl font-semibold mb-4">{{ __('Bids Received') }}</h3>

            @if($bids->isEmpty())
                <p class="text-gray-600">{{ __('No bids have been placed for this auction yet.') }}</p>
            @else
                <form action="{{ route('auctions.end', $auction) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="w-full table-auto">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">{{ __('Bidder') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('Bid Amount') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('Bid Time') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('Select Winner') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bids as $bid)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <a href="{{ route('profile.show', $bid->user) }}" class="text-blue-500 hover:underline">
                                        {{ $bid->user->fullname ?? $bid->user->username }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">€{{ number_format($bid->price, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $bid->created_at }}</td>
                                <td class="px-4 py-2 text-center">
                                    <input type="radio" name="winner_id" value="{{ $bid->user_id }}" required>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                            {{ __('End Auction and Select Winner') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Botão para Cancelar (voltar) -->
        <div class="flex justify-end">
            <a href="{{ route('auctions.show', $auction) }}" class="text-gray-700 hover:underline">
                {{ __('Cancel') }}
            </a>
        </div>
    </div>
@endsection
