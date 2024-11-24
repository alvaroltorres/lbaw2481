@include('partials.header')

<main>
    <section class="auctions">
        <div class="container">
            <h1>{{ __('Explore Auctions') }}</h1>
            <div class="auction-grid">
                @foreach($activeAuctions as $auction)
                    <div class="auction-card">
                        <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ __($auction->title) }}">
                        <h2>{{ __($auction->title) }}</h2>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>{{ __('Current Bid') }}: ${{ number_format($auction->current_price, 2) }}</p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">{{ __('View Auction') }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

@include('partials.footer')
