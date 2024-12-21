@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="filters-form mb-4">
            <div>
                <label for="min_price">{{ __('Minimum Price:') }}</label>
                <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="{{ __('Enter minimum price') }}">
            </div>
            <div>
                <label for="max_price">{{ __('Maximum Price:') }}</label>
                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="{{ __('Enter maximum price') }}">
            </div>
            <div>
                <label for="status">{{ __('Status:') }}</label>
                <select name="status" id="status">
                    <option value="">{{ __('All') }}</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>{{ __('Upcoming') }}</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                </select>
            </div>
            <div>
                <label for="sort_by">{{ __('Sort By:') }}</label>
                <select name="sort_by" id="sort_by">
                    <option value="">{{ __('None') }}</option>
                    <option value="recent" {{ request('sort_by') == 'recent' ? 'selected' : '' }}>{{ __('Recent') }}</option>
                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">{{ __('Apply Filters') }}</button>
        </form>

        <h1>{{ __('Search Results for') }} "{{ __($query) }}"</h1>

        @if($auctions->isEmpty())
            <p>{{ __('No auctions found.') }}</p>
        @else
            <div class="list-group">
                @foreach($auctions as $auction)
                    <a href="{{ route('auctions.show', $auction->auction_id) }}" class="list-group-item list-group-item-action">
                        <h5 class="mb-1">{{ __($auction->title) }}</h5>
                        <p class="mb-1">{{ __($auction->description) }}</p>
                        <p class="status">{{ __($auction->status) }}</p>
                        <small>{{ __('Category') }}: {{ __($auction->category->name) }}</small><br>
                        <small>{{ __('Current Price') }}: ${{ number_format($auction->current_price, 2) }}</small>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection