@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('User Ratings') }}</h1>

        @if($ratings->count() > 0)
            @foreach($ratings as $rating)
                @php
                    $auction = $rating->transaction->auction ?? null;
                    $rater   = $rating->raterUser;
                @endphp
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>{{ __('Score') }}:</strong>
                            @for($i=1; $i<=$rating->score; $i++)
                                <i class="fas fa-star" style="color:#ffc107;"></i>
                            @endfor
                        </p>
                        <p><strong>{{ __('Comment') }}:</strong> {{ $rating->comment ?? '—' }}</p>
                        <p><strong>{{ __('Rated by') }}:</strong> {{ $rater->fullname }} ({{ $rater->email }})</p>
                        @if($auction)
                            <p><strong>{{ __('Auction') }}:</strong>
                                <a href="{{ route('auctions.show', $auction->auction_id) }}">
                                    {{ $auction->title }}
                                </a>
                            </p>
                        @endif
                        <p><small>{{ __('Date') }}: {{ $rating->rating_time }}</small></p>
                    </div>
                </div>
            @endforeach
        @else
            <p>{{ __('No ratings yet.') }}</p>
        @endif
    </div>
@endsection
