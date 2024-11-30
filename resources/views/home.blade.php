@extends('layouts.app')

@section('content')
    <!-- Seção Hero -->
    <section class="hero" id="hero">
        <div class="hero__overlay"></div>
        <div class="container hero__content">
            <h1>{{ __('Discover the Most Sought-After Auctions') }}</h1>
            <p>{{ __('Join exclusive auctions with an interactive and secure experience.') }}</p>
            <a href="{{ route('auctions.index') }}" class="btn btn--primary">{{ __('Explore Auctions') }}</a>

            <!-- Botão para Admins -->
            @if(Auth::check() && Auth::user()->is_admin)
                <a href="{{ route('admin.users.index') }}" class="btn btn--secondary">{{ __('Manage Users') }}</a>
            @endif
        </div>
    </section>

    <!-- Seção de Recursos -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">{{ __('Experience the Future of Online Auctions') }}</h2>
            <div class="features__grid">
                <!-- Recurso 1 -->
                <div class="feature-item">
                    <i class="fas fa-bolt feature-icon" aria-hidden="true"></i>
                    <h3 class="feature-title">{{ __('Real-Time Bidding') }}</h3>
                    <p class="feature-description">{{ __('Engage in auctions with live updates and real-time bidding capabilities.') }}</p>
                </div>
                <!-- Recurso 2 -->
                <div class="feature-item">
                    <i class="fas fa-gem feature-icon" aria-hidden="true"></i>
                    <h3 class="feature-title">{{ __('Exclusive Items') }}</h3>
                    <p class="feature-description">{{ __('Access a curated selection of rare and exclusive items.') }}</p>
                </div>
                <!-- Recurso 3 -->
                <div class="feature-item">
                    <i class="fas fa-mobile-alt feature-icon" aria-hidden="true"></i>
                    <h3 class="feature-title">{{ __('Mobile Friendly') }}</h3>
                    <p class="feature-description">{{ __('Enjoy a seamless experience across all your devices.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Leilões Ativos -->
    <section class="active-auctions" id="active-auctions">
        <div class="container">
            <h2 class="section-title">{{ __('Active Auctions') }}</h2>
            <div class="auction-grid">
                <!-- Verificar se há leilões ativos -->
                @if($activeAuctions->count() > 0)
                    @foreach($activeAuctions as $auction)
                        <article class="auction-card">
                            <!-- Exibir imagem do leilão -->
                            @if($auction->image)
                                <img src="{{ asset('images/auctions/' . $auction->image) }}" alt="{{ $auction->title }}" class="auction-image">
                            @else
                                <img src="{{ asset('images/auctions/default.png') }}" alt="{{ $auction->title }}" class="auction-image">
                            @endif
                            <div class="auction-details">
                                <h3 class="auction-title">{{ $auction->title }}</h3>
                                <p class="auction-description">{{ Str::limit($auction->description, 100) }}</p>
                                <div class="auction-meta">
                                    <span class="auction-price">${{ number_format($auction->current_price ?? $auction->starting_price, 2, ',', '.') }}</span>
                                    <span class="auction-timer" data-end-time="{{ $auction->ending_date->toIso8601String() }}">{{ $auction->ending_date->diffForHumans() }}</span>
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
                <!-- Verificar se há leilões futuros -->
                @if($upcomingAuctions->count() > 0)
                    @foreach($upcomingAuctions as $futureauction)
                        <article class="auction-card">
                            <!-- Exibir imagem do leilão -->
                            @if($futureauction->image)
                                <img src="{{ asset('images/auctions/' . $futureauction->image) }}" alt="{{ $futureauction->title }}" class="auction-image">
                            @else
                                <img src="{{ asset('images/auctions/default.png') }}" alt="{{ $futureauction->title }}" class="auction-image">
                            @endif
                            <div class="auction-details">
                                <h3 class="auction-title">{{ $futureauction->title }}</h3>
                                <p class="auction-description">{{ Str::limit($futureauction->description, 100) }}</p>
                                <div class="auction-meta">
                                    <span class="auction-price">${{ number_format($futureauction->current_price ?? $futureauction->starting_price, 2, ',', '.') }}</span>
                                    <span class="auction-timer" data-end-time="{{ $futureauction->ending_date->toIso8601String() }}">{{ $futureauction->ending_date->diffForHumans() }}</span>
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
    </section>

    <!-- Seção de Depoimentos -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title">{{ __('What Our Users Say') }}</h2>
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
    </section>
@endsection
