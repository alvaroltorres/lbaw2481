@include('partials.header')

<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2>Create Auction</h2>

    <form action="{{ route('auctions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <!-- You can dynamically populate categories here -->
                <option value="" disabled selected>Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="starting_price" class="form-label">Starting Price</label>
            <input type="number" step="0.01" class="form-control" id="starting_price" name="starting_price" value="{{ old('starting_price') }}" required>
            @error('starting_price')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reserve_price" class="form-label">Reserve Price</label>
            <input type="number" step="0.01" class="form-control" id="reserve_price" name="reserve_price" value="{{ old('reserve_price') }}" required>
            @error('reserve_price')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="minimum_bid_increment" class="form-label">Minimum Bid Increment</label>
            <input type="number" step="0.01" class="form-control" id="minimum_bid_increment" name="minimum_bid_increment" value="{{ old('minimum_bid_increment') }}" required>
            @error('minimum_bid_increment')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="starting_date" class="form-label">Starting Date</label>
            <input type="datetime-local" class="form-control" id="starting_date" name="starting_date" value="{{ old('starting_date') }}" required>
            @error('starting_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ending_date" class="form-label">Ending Date</label>
            <input type="datetime-local" class="form-control" id="ending_date" name="ending_date" value="{{ old('ending_date') }}" required>
            @error('ending_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
            @error('location')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
            @error('description')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
            @error('status')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Auction</button>
    </form>
</div>

@include('partials.footer')
