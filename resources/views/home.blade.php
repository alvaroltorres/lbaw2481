<!-- resources/views/home.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Metadados Básicos -->
    <meta charset="UTF-8">
    <title>{{ __('BidZenith - Discover the Most Sought-After Auctions') }}</title>
    <meta name="description" content="{{ __('Join exclusive auctions with an interactive and secure experience.') }}">
    <meta name="keywords" content="{{ __('auctions, bidding, online auctions, BidZenith') }}">
    <meta name="author" content="BidZenith">

    <!-- Metas Específicas para Mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome para Ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS e JS Compilados pelo Vite -->
    @vite(['resources/css/style.css', 'resources/js/main.js'])

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
<!-- Incluir o Header -->
@include('partials.header')

<!-- Exibir Locale Atual para Debug -->
<div class="container mt-4">
    <p>Current Locale: {{ App::getLocale() }}</p>
    <p>{{ __('Test String') }}</p>
</div>

<!-- Conteúdo Principal -->
<main>
    <!-- Seção Hero -->
    <section class="hero" id="hero">
        <div class="hero__overlay"></div>
        <div class="container hero__content">
            <h1>{{ __('Discover the Most Sought-After Auctions') }}</h1>
            <p>{{ __('Join exclusive auctions with an interactive and secure experience.') }}</p>
            <a href="{{ route('auction.index') }}" class="btn btn--primary">{{ __('Explore Auctions') }}</a>
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
                <!-- Loop através dos leilões ativos -->
                @foreach($activeAuctions as $auction)
                    <article class="auction-card">
                        <img src="{{ asset($auction->image) }}" alt="{{ $auction->title }}" class="auction-image">
                        <div class="auction-details">
                            <h3 class="auction-title">{{ $auction->title }}</h3>
                            <p class="auction-description">{{ Str::limit($auction->description, 100) }}</p>
                            <div class="auction-meta">
                                <span class="auction-price">${{ number_format($auction->current_bid, 2) }}</span>
                                <span class="auction-timer" id="timer-{{ $auction->id }}">{{ __('00:00:00') }}</span>
                            </div>
                            <a href="{{ route('auction.show', $auction) }}" class="btn btn--secondary">{{ __('Participate') }}</a>
                        </div>
                    </article>
                @endforeach
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
</main>

<!-- Incluir o Footer -->
@include('partials.footer')

<!-- Scripts -->
<script>
    const activeAuctions = @json($activeAuctions);

    function changeLanguage(locale) {
        console.log("Changing language to:", locale); // Log the selected locale
        window.location.href = `/lang/${locale}`;
    }
</script>
</body>
</html>
