@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Edit Auction') }}</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>{{ __('There were some problems with your input.') }}</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Auction Update Form -->
        <form method="POST" action="{{ route('auctions.update', $auction) }}">
            @csrf
            @method('PATCH')

            <!-- Title -->
            <div class="form-group">
                <label for="title">{{ __('Title') }}</label>
                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $auction->title) }}" required>

                @error('title')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">{{ __('Description') }}</label>
                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description', $auction->description) }}</textarea>

                @error('description')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category_id">{{ __('Category') }}</label>
                <select id="category_id" class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $auction->category_id == $category->category_id ? 'selected' : '' }}>
                            {{ __($category->name) }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Starting Price -->
            <div class="form-group">
                <label for="starting_price">{{ __('Starting Price') }}</label>
                <input id="starting_price" type="number" step="0.01" class="form-control @error('starting_price') is-invalid @enderror" name="starting_price" value="{{ old('starting_price', $auction->starting_price) }}" required>

                @error('starting_price')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Reserve Price -->
            <div class="form-group">
                <label for="reserve_price">{{ __('Reserve Price') }}</label>
                <input id="reserve_price" type="number" step="0.01" class="form-control @error('reserve_price') is-invalid @enderror" name="reserve_price" value="{{ old('reserve_price', $auction->reserve_price) }}" required>

                @error('reserve_price')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Minimum Bid Increment -->
            <div class="form-group">
                <label for="minimum_bid_increment">{{ __('Minimum Bid Increment') }}</label>
                <input id="minimum_bid_increment" type="number" step="0.01" class="form-control @error('minimum_bid_increment') is-invalid @enderror" name="minimum_bid_increment" value="{{ old('minimum_bid_increment', $auction->minimum_bid_increment) }}" required>

                @error('minimum_bid_increment')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Starting Date -->
            <div class="form-group">
                <label for="starting_date">{{ __('Starting Date') }}</label>
                <input id="starting_date" type="datetime-local" class="form-control @error('starting_date') is-invalid @enderror" name="starting_date" value="{{ old('starting_date', $auction->starting_date) }}" required>

                @error('starting_date')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Ending Date -->
            <div class="form-group">
                <label for="ending_date">{{ __('Ending Date') }}</label>
                <input id="ending_date" type="datetime-local" class="form-control @error('ending_date') is-invalid @enderror" name="ending_date" value="{{ old('ending_date', $auction->ending_date) }}" required>

                @error('ending_date')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Location -->
            <div class="form-group">
                <label for="location">{{ __('Location') }}</label>
                <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $auction->location) }}" required>

                @error('location')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                    <option value="Active" {{ $auction->status == 'Active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="Upcoming" {{ $auction->status == 'Upcoming' ? 'selected' : '' }}>{{ __('Upcoming') }}</option>
                </select>

                @error('status')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Update Auction') }}
                </button>
            </div>
        </form>
    </div>
@endsection
