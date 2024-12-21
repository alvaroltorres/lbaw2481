@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ __('My Ratings') }}</h1>

        @if($ratings->count() > 0)
            <div class="ratings-list" style="margin-top:1rem;">
                @foreach($ratings as $rating)
                    <div class="rating-item" style="border:1px solid #ddd; padding:1rem; border-radius:8px; margin-bottom:1rem;">
                        <p><strong>{{ __('Score') }}:</strong>
                            @for($i=1; $i<=$rating->score; $i++)
                                <i class="fas fa-star" style="color:#ffc107;"></i>
                            @endfor
                        </p>
                        <p><strong>{{ __('Comment') }}:</strong> {{ $rating->comment }}</p>
                        <p><small>{{ __('Rated by') }}:
                                @php
                                    $rater = \App\Models\User::find($rating->rater_user_id);
                                @endphp
                                <a href="{{ route('user.show', $rating->rater_user_id) }}">{{ $rater ? $rater->username : 'Unknown' }}</a>
                                {{ $rating->rating_time }}</small>
                        </p>
                    </div>
                @endforeach
            </div>
            {{ $ratings->links() }}
        @else
            <p>{{ __('You have no ratings yet.') }}</p>
        @endif
    </div>
@endsection
