@extends('layouts.app')

@section('content')
    <div class="profile-div">
        <h1>{{ __('Edit Auction') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('auctions.update', $auction->auction_id) }}" class="card">
            @csrf
            @method('PATCH')

            <div class="card-body">
                <!-- Title -->
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $auction->title) }}" required placeholder="{{ __('Enter auction title') }}">

                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category_id">{{ __('Category') }}</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="" disabled>{{ __('Select a category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ $auction->category_id == $category->category_id ? 'selected' : '' }}>{{ __($category->name) }}</option>
                        @endforeach
                    </select>

                    @error('category_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Starting Price -->
                <div class="form-group">
                    <label for="starting_price">{{ __('Starting Price') }}</label>
                    <input id="starting_price" type="number" step="0.01" class="form-control @error('starting_price') is-invalid @enderror" name="starting_price" value="{{ old('starting_price', $auction->starting_price) }}" required placeholder="{{ __('Enter starting price') }}">

                    @error('starting_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Reserve Price -->
                <div class="form-group">
                    <label for="reserve_price">{{ __('Reserve Price') }}</label>
                    <input id="reserve_price" type="number" step="0.01" class="form-control @error('reserve_price') is-invalid @enderror" name="reserve_price" value="{{ old('reserve_price', $auction->reserve_price) }}" required placeholder="{{ __('Enter reserve price') }}">

                    @error('reserve_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Minimum Bid Increment -->
                <div class="form-group">
                    <label for="minimum_bid_increment">{{ __('Minimum Bid Increment') }}</label>
                    <input id="minimum_bid_increment" type="number" step="0.01" class="form-control @error('minimum_bid_increment') is-invalid @enderror" name="minimum_bid_increment" value="{{ old('minimum_bid_increment', $auction->minimum_bid_increment) }}" required placeholder="{{ __('Enter minimum bid increment') }}">

                    @error('minimum_bid_increment')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Starting Date -->
                <div class="form-group">
                    <label for="starting_date">{{ __('Starting Date') }}</label>
                    <input id="starting_date" type="datetime-local" class="form-control @error('starting_date') is-invalid @enderror" name="starting_date" value="{{ old('starting_date', $auction->starting_date) }}" required placeholder="{{ __('Select starting date and time') }}">

                    @error('starting_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Ending Date -->
                <div class="form-group">
                    <label for="ending_date">{{ __('Ending Date') }}</label>
                    <input id="ending_date" type="datetime-local" class="form-control @error('ending_date') is-invalid @enderror" name="ending_date" value="{{ old('ending_date', $auction->ending_date) }}" required placeholder="{{ __('Select ending date and time') }}">

                    @error('ending_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label for="location">{{ __('Location') }}</label>
                    <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $auction->location) }}" required placeholder="{{ __('Enter location') }}">

                    @error('location')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="4" required placeholder="{{ __('Enter auction description') }}">{{ old('description', $auction->description) }}</textarea>

                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">{{ __('Status') }}</label>
                    <select id="status" class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="Active" {{ $auction->status == 'Active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="Closed" {{ $auction->status == 'Closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                        <option value="Sold" {{ $auction->status == 'Sold' ? 'selected' : '' }}>{{ __('Sold') }}</option>
                        <option value="Unsold" {{ $auction->status == 'Unsold' ? 'selected' : '' }}>{{ __('Unsold') }}</option>
                    </select>

                    @error('status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Save Changes Button -->
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection