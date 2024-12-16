@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ __('Add Credits') }}</h2>

        <form action="{{ route('credits.add.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="credits">{{ __('Amount to Add') }} ($)</label>
                <input type="number" name="credits" id="credits" class="form-control" step="0.01" min="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Pay') }}</button>
        </form>
    </div>
@endsection
