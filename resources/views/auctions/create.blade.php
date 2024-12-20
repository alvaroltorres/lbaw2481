@extends('layouts.app')

@section('content')
    <div class="container create-auction-page">
        <h2>{{ __('Create Auction') }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('auctions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">{{ __('Title') }}</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="{{ __('Enter auction title') }}">
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">{{ __('Category') }}</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" disabled selected>{{ __('Select a category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="starting_price" class="form-label">{{ __('Starting Price') }} (€)</label>
                <input type="number" step="0.01" class="form-control" id="starting_price" name="starting_price" value="{{ old('starting_price') }}" required placeholder="{{ __('e.g. 10.00') }}">
            </div>

            <div class="mb-3">
                <label for="reserve_price" class="form-label">{{ __('Reserve Price') }} (€)</label>
                <input type="number" step="0.01" class="form-control" id="reserve_price" name="reserve_price" value="{{ old('reserve_price') }}" required placeholder="{{ __('e.g. 100.00') }}">
            </div>

            <div class="mb-3">
                <label for="minimum_bid_increment" class="form-label">{{ __('Minimum Bid Increment') }} (€)</label>
                <input type="number" step="0.01" class="form-control" id="minimum_bid_increment" name="minimum_bid_increment" value="{{ old('minimum_bid_increment') }}" required placeholder="{{ __('e.g. 1.00') }}">
            </div>

            <div class="mb-3">
                <label for="starting_date" class="form-label">{{ __('Starting Date') }}</label>
                <input type="datetime-local" class="form-control" id="starting_date" name="starting_date" value="{{ old('starting_date') }}" required>
            </div>

            <div class="mb-3">
                <label for="ending_date" class="form-label">{{ __('Ending Date') }}</label>
                <input type="datetime-local" class="form-control" id="ending_date" name="ending_date" value="{{ old('ending_date') }}" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">{{ __('Location') }}</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required placeholder="{{ __('City, Country') }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="{{ __('Write a brief description') }}">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">{{ __('Status') }}</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Create Auction') }}</button>
        </form>
    </div>
@endsection
