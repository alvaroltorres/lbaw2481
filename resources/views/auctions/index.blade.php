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
                            @if($auction->image)
                                <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ __($auction->title) }}">
                            @else
                                <img src="{{ asset('images/auctions/default.png') }}" alt="{{ __($auction->title) }}">
                            @endif
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
                            <!-- Adicionando o Timer -->
                            <p>{{ __('Time Remaining') }}: <span class="auction-timer" data-end-time="{{ $auction->ending_date->toIso8601String() }}"></span></p>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timers = document.querySelectorAll('.auction-timer');
            let timerElements = [];

            // Inicializa os timers
            timers.forEach(timer => {
                const endTime = new Date(timer.getAttribute('data-end-time'));
                if (!isNaN(endTime)) { // Verifica se a data é válida
                    timerElements.push({ element: timer, endTime: endTime });
                }
            });

            // Função para calcular a diferença de tempo
            function getTimeDifference(end, now) {
                let years = end.getFullYear() - now.getFullYear();
                let months = end.getMonth() - now.getMonth();
                let days = end.getDate() - now.getDate();
                let hours = end.getHours() - now.getHours();
                let minutes = end.getMinutes() - now.getMinutes();
                let seconds = end.getSeconds() - now.getSeconds();

                if (seconds < 0) {
                    seconds += 60;
                    minutes--;
                }
                if (minutes < 0) {
                    minutes += 60;
                    hours--;
                }
                if (hours < 0) {
                    hours += 24;
                    days--;
                }
                if (days < 0) {
                    // Obtém o número de dias no mês anterior
                    const previousMonth = new Date(end.getFullYear(), end.getMonth(), 0);
                    days += previousMonth.getDate();
                    months--;
                }
                if (months < 0) {
                    months += 12;
                    years--;
                }

                return { years, months, days, hours, minutes, seconds };
            }

            // Função para atualizar todos os timers
            function updateAllTimers() {
                const now = new Date();
                let activeTimers = [];

                timerElements.forEach(timerObj => {
                    const { endTime, element } = timerObj;
                    if (endTime <= now) {
                        element.textContent = '{{ __("Auction ended") }}';
                    } else {
                        const diff = getTimeDifference(endTime, now);
                        let timeString = '';

                        if (diff.years > 0) {
                            timeString += `${diff.years}{{ __('y') }} `;
                        }
                        if (diff.months > 0) {
                            timeString += `${diff.months}{{ __('m') }} `;
                        }
                        if (diff.days > 0) {
                            timeString += `${diff.days}{{ __('d') }} `;
                        }
                        if (diff.hours > 0) {
                            timeString += `${diff.hours}{{ __('h') }} `;
                        }
                        if (diff.minutes > 0) {
                            timeString += `${diff.minutes}{{ __('m') }} `;
                        }
                        if (diff.seconds > 0) {
                            timeString += `${diff.seconds}{{ __('s') }}`;
                        }

                        element.textContent = timeString.trim();
                        activeTimers.push(timerObj); // Mantém o timer ativo
                    }
                });

                timerElements = activeTimers; // Atualiza a lista de timers ativos

@include('partials.footer')