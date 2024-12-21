@include('partials.header')

<main>
    <section class="auctions">
        <div class="container">
            <h1>{{ __('Explore Auctions') }}</h1>

            <!-- Filters -->
            <form action="{{ route('auctions.index') }}" method="GET" class="filters-form">
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

            <!-- Auction List -->
            <div class="auction-grid">
                @forelse($activeAuctions as $auction)
                    <div class="auction-card">
                        <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ __($auction->title) }}">
                        <h2>{{ __($auction->title) }}</h2>
                        <p>{{ __($auction->description) }}</p>
                        <p class="status">{{ __($auction->status) }}</p>
                        <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
                        <p>
                            <strong>{{ __('Seller:') }}</strong>
                            <a href="{{ route('user.show', $auction->seller->user_id) }}">
                                {{ __($auction->seller->fullname) }}
                            </a>
                        </p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>
                        @if(auth()->user()->is_admin || $auction->user_id === auth()->id())
                            <form action="{{ route('auctions.destroy', $auction->auction_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('Cancel Auction') }}</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p>{{ __('No auctions found for the applied filters.') }}</p>
                @endforelse
            </div>
        </div>
    </section>
</main>

@include('partials.footer')