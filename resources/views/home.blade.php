@extends('layouts.app')

@section('title', __('Welcome to BidZenith'))

@section('og:title', __('Welcome to BidZenith'))
@section('og:description', __('Discover the most sought-after auctions. Join exclusive auctions with an interactive and secure experience.'))
@section('og:image', asset('public/images/logo.png'))
@section('og:url', url('/'))

@section('content')
    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero__overlay"></div>
        <div class="container hero__content">
            <h1>{{ __('Welcome to BidZenith') }}</h1>
            <h2>{{ __('Discover the Most Sought-After Auctions') }}</h2>
            <p>{{ __('Join exclusive auctions with an interactive and secure experience.') }}</p>
            @if(Auth::check())
                <a href="{{ route('auctions.index') }}" class="btn btn--primary">{{ __('Explore Auctions') }}</a>
                <a href="{{ route('auctions.followed') }}" class="btn btn--primary">{{ __('View Followed Auctions') }}</a>
            @else
                <a href="{{ route('login') }}" class="btn btn--primary">{{ __('Explore Auctions') }}</a>
            @endif

            @if(Auth::check() && Auth::user()->is_admin)
                <a href="{{ route('admin.users.index') }}" class="btn btn--secondary">{{ __('Manage Users') }}</a>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="tooltip" data-tooltip="{{ __('Here you can find the main features of our platform.') }}">{{ __('Experience the Future of Online Auctions') }}</h2>
            <div class="features__grid">
                <div class="feature-item">
                    <i class="fas fa-bolt feature-icon" aria-hidden="true"></i>
                    <h3 class="feature-title">{{ __('Real-Time Bidding') }}</h3>
                    <p class="feature-description">{{ __('Engage in auctions with live updates and real-time bidding capabilities.') }}</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-gem feature-icon" aria-hidden="true"></i>
                    <h3 class="feature-title">{{ __('Exclusive Items') }}</h3>
                    <p class="feature-description">{{ __('Access a curated selection of rare and exclusive items.') }}</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-mobile-alt feature-icon" aria-hidden="true"></i>
                    <h3 class="feature-title">{{ __('Mobile Friendly') }}</h3>
                    <p class="feature-description">{{ __('Enjoy a seamless experience across all your devices.') }}</p>
                </div>
            </div>
        </div>
        <div class="help-button-container">
            <button type="button" class="help-button" data-modal="featuresHelpModal">{{ __('Help') }}</button>
        </div>
    </section>

    <!-- Active Auctions Section -->
    <section class="active-auctions" id="active-auctions">
        <div class="container">
            <h2 class="tooltip" data-tooltip="{{ __('View all active auctions here.') }}">{{ __('Active Auctions') }}</h2>
            <div class="auction-grid">
                @if($activeAuctions->count() > 0)
                    @foreach($activeAuctions as $auction)
                        <article class="auction-card">
                            @if($auction->image)
                                <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}" class="auction-image">
                            @else
                                <img src="{{ asset('images/auctions/default.png') }}" alt="{{ $auction->title }}" class="auction-image">
                            @endif
                            <div class="auction-details">
                                <h3 class="auction-title">{{ $auction->title }}</h3>
                                <p class="auction-description">{{ Str::limit($auction->description, 100) }}</p>
                                <div class="auction-meta">
                                    <span class="auction-price">€{{ number_format($auction->current_price ?? $auction->starting_price, 2, ',', '.') }}</span>
                                    <span class="auction-timer" data-end-time="{{ $auction->ending_date->toIso8601String() }}"></span>
                                </div>
                                <a href="{{ route('auctions.show', $auction) }}" class="btn btn--secondary">{{ __('Participate') }}</a>
                            </div>
                        </article>
                    @endforeach
                @else
                    <p>{{ __('No active auctions at the moment.') }}</p>
                @endif
            </div>

            <h2 class="section-title">{{ __('Upcoming Auctions') }}</h2>
            <div class="auction-grid">
                @if($upcomingAuctions->count() > 0)
                    @foreach($upcomingAuctions as $futureauction)
                        <article class="auction-card">
                            @if($futureauction->image)
                                <img src="{{ asset('images/auctions/' . $futureauction->image) }}" alt="{{ $futureauction->title }}" class="auction-image">
                            @else
                                <img src="{{ asset('images/auctions/default.png') }}" alt="{{ $futureauction->title }}" class="auction-image">
                            @endif
                            <div class="auction-details">
                                <h3 class="auction-title">{{ $futureauction->title }}</h3>
                                <p class="auction-description">{{ Str::limit($futureauction->description, 100) }}</p>
                                <div class="auction-meta">
                                    <span class="auction-price">€{{ number_format($futureauction->current_price ?? $futureauction->starting_price, 2, ',', '.') }}</span>
                                    <span class="auction-timer" data-end-time="{{ $futureauction->ending_date->toIso8601String() }}"></span>
                                </div>
                                <a href="{{ route('auctions.show', $futureauction) }}" class="btn btn--secondary">{{ __('Participate') }}</a>
                            </div>
                        </article>
                    @endforeach
                @else
                    <p>{{ __('No upcoming auctions.') }}</p>
                @endif
            </div>
        </div>
        <div class="help-button-container">
            <button type="button" class="help-button" data-modal="activeAuctionsHelpModal">{{ __('Help') }}</button>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2 class="tooltip" data-tooltip="{{ __('Read testimonials from our satisfied users.') }}">{{ __('What Our Users Say') }}</h2>
            <div class="testimonials__grid">
                <div class="testimonial-item">
                    <p class="testimonial-text">"BidZenith has transformed my online auction experience. Intuitive and secure platform!"</p>
                    <h4 class="testimonial-author">- Alex Johnson</h4>
                </div>
                <div class="testimonial-item">
                    <p class="testimonial-text">"I love the real-time chat! It makes bidding much more interactive and exciting."</p>
                    <h4 class="testimonial-author">- Sophia Lee</h4>
                </div>
                <div class="testimonial-item">
                    <p class="testimonial-text">"The security measures give me total confidence to make my transactions."</p>
                    <h4 class="testimonial-author">- Michael Smith</h4>
                </div>
            </div>
        </div>
        <div class="help-button-container">
            <button type="button" class="help-button" data-modal="testimonialsHelpModal">{{ __('Help') }}</button>
        </div>
    </section>

    <!-- Modals de Ajuda -->
    <div id="featuresHelpModal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal="featuresHelpModal">&times;</span>
            <h2>{{ __('Features Help') }}</h2>
            <p>{{ __('Here you can find the main features of our platform.') }}</p>
        </div>
    </div>

    <div id="activeAuctionsHelpModal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal="activeAuctionsHelpModal">&times;</span>
            <h2>{{ __('Active Auctions Help') }}</h2>
            <p>{{ __('View all active auctions here. Just click and participate in an auction.') }}</p>
        </div>
    </div>

    <div id="testimonialsHelpModal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal="testimonialsHelpModal">&times;</span>
            <h2>{{ __('Testimonials Help') }}</h2>
            <p>{{ __('Read testimonials from our satisfied users.') }}</p>
        </div>
    </div>
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

                // Se todos os timers terminaram, para o intervalo
                if (timerElements.length === 0) {
                    clearInterval(timerInterval);
                }
            }

            // Atualiza os timers imediatamente e depois a cada segundo
            updateAllTimers();
            const timerInterval = setInterval(updateAllTimers, 1000);
        });
    </script>
@endpush

