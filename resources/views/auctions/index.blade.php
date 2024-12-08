@include('partials.header')

<main>
    <section class="auctions">
        <div class="container">
            <h1>{{ __('Explore Auctions') }}</h1>

            <!-- Filtros -->
            <form action="{{ route('auctions.index') }}" method="GET" class="filters-form">
                <div>
                    <label for="category">{{ __('Category:') }}</label>
                    <select name="category" id="category">
                        <option value="">{{ __('All') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @if($category->subcategories)
                                @foreach($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->category_id }}" {{ request('category') == $subcategory->category_id ? 'selected' : '' }}>
                                        -- {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="min_price">{{ __('Minimum Price:') }}</label>
                    <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="Enter minimum price">
                </div>
                <div>
                    <label for="max_price">{{ __('Maximum Price:') }}</label>
                    <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="Enter maximum price">
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
                <button type="submit" class="btn btn-secondary">{{ __('Apply Filters') }}</button>
            </form>

            <!-- Lista de Leilões -->
            <div class="auction-grid">
                @forelse($activeAuctions as $auction)
                    <div class="auction-card">
                        <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
                        <h2>{{ $auction->title }}</h2>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
                        <p>
                            <strong>{{ __('Seller:') }}</strong>
                            <a href="{{ route('user.show', $auction->seller->user_id) }}">
                                {{ $auction->seller->fullname }}
                            </a>
                        </p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>
                    </div>
                @empty
                    <p>{{ __('No auctions found for the applied filters.') }}</p>
                @endforelse
            </div>
        </div>
    </section>
</main>

@include('partials.footer')
