@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Rate This Transaction') }}</h1>

        <div class="card p-3">
            <h3>{{ $auction->title }}</h3>
            <p>{{ Str::limit($auction->description, 100) }}</p>
            <p><strong>{{ __('Seller ID') }}:</strong> {{ $auction->user_id }}</p>
            <p><strong>{{ __('Amount Paid') }}:</strong> €{{ number_format($transaction->value, 2) }}</p>
        </div>

        <form action="{{ route('ratings.store') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">

            <div class="form-group">
                <label for="score">{{ __('Score (1-5)') }}</label>
                <input type="number" name="score" id="score" class="form-control" min="1" max="5" required>
                @error('score')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="comment">{{ __('Comment (optional)') }}</label>
                <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
                @error('comment')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button class="btn btn-primary mt-3" type="submit">{{ __('Submit Rating') }}</button>
        </form>
    </div>
@endsection
