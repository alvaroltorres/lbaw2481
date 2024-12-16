@include('partials.header')

<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2>{{ __('Create Auction') }}</h2>

    <form action="{{ route('auctions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="{{ __('Enter auction title') }}">
            @error('title')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">{{ __('Category') }}</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                <option value="" disabled selected>{{ __('Select a category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}">{{ __($category->name) }}</option>
                @endforeach
            </select>
            @error('category_id')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="starting_price" class="form-label">{{ __('Starting Price') }}</label>
            <input type="number" step="0.01" class="form-control @error('starting_price') is-invalid @enderror" id="starting_price" name="starting_price" value="{{ old('starting_price') }}" required placeholder="{{ __('Enter starting price') }}">
            @error('starting_price')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reserve_price" class="form-label">{{ __('Reserve Price') }}</label>
            <input type="number" step="0.01" class="form-control @error('reserve_price') is-invalid @enderror" id="reserve_price" name="reserve_price" value="{{ old('reserve_price') }}" required placeholder="{{ __('Enter reserve price') }}">
            @error('reserve_price')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="minimum_bid_increment" class="form-label">{{ __('Minimum Bid Increment') }}</label>
            <input type="number" step="0.01" class="form-control" id="minimum_bid_increment" name="minimum_bid_increment" value="{{ old('minimum_bid_increment') }}" required placeholder="{{ __('Enter minimum bid increment') }}">
            @error('minimum_bid_increment')
            <div class="text-danger">{{ __($message) }}</div>
            @enderror
        </div>


        <div class="mb-3">
            <label for="starting_date" class="form-label">{{ __('Starting Date') }}</label>
            <input type="datetime-local" class="form-control @error('starting_date') is-invalid @enderror" id="starting_date" name="starting_date" value="{{ old('starting_date') }}" required placeholder="{{ __('Select starting date and time') }}">
            @error('starting_date')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ending_date" class="form-label">{{ __('Ending Date') }}</label>
            <input type="datetime-local" class="form-control @error('ending_date') is-invalid @enderror" id="ending_date" name="ending_date" value="{{ old('ending_date') }}" required placeholder="{{ __('Select ending date and time') }}">
            @error('ending_date')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">{{ __('Location') }}</label>
            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" required placeholder="{{ __('Enter location') }}">
            @error('location')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required placeholder="{{ __('Enter auction description') }}">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ __($message) }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
            </select>
            @error('status')
            <div class="text-danger">{{ __($message) }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Create Auction') }}</button>
    </form>
</div>

@include('partials.footer')