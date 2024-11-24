
@include('partials.header')

<main>
    <section class="auctions">
        <div class="container">
            <h1>Explore Auctions</h1>

            <!-- Filtros -->
            <form action="{{ route('auctions.index') }}" method="GET" class="filters-form">
                <div>
                    <label for="category">Categoria:</label>
                    <select name="category" id="category">
                        <option value="">Todas</option>
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
                    <label for="min_price">Preço Mínimo:</label>
                    <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}">
                </div>
                <div>
                    <label for="max_price">Preço Máximo:</label>
                    <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}">
                </div>
                <div>
                    <label for="status">Estado:</label>
                    <select name="status" id="status">
                        <option value="">Todos</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Ativo</option>
                        <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Próximo</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Concluído</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Aplicar Filtros</button>
            </form>

            <!-- Lista de Leilões -->
            <div class="auction-grid">
                @forelse($activeAuctions as $auction)
                    <div class="auction-card">
                        <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}">
                        <h2>{{ $auction->title }}</h2>
                        <p>{{ Str::limit($auction->description, 100) }}</p>
                        <p>Current Bid: ${{ number_format($auction->current_price, 2) }}</p>
                        <p>
                            <strong>Vendedor:</strong>
                            <a href="{{ route('admin.users.show', $auction->seller->user_id) }}">
                                {{ $auction->seller->fullname }}
                            </a>
                        </p>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">View Auction</a>
                    </div>
                @empty
                    <p>Não foram encontrados leilões para os filtros aplicados.</p>
                @endforelse
            </div>
        </div>
    </section>
</main>

@include('partials.footer')
