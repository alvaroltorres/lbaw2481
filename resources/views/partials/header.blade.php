<!-- Header Section -->
<header class="header">
    <div class="container header__container flex items-center justify-between">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('home') }}" aria-label="{{ __('BidZenith Home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="BidZenith Logo" class="logo-img">
            </a>
        </div>

        <!-- Search Bar -->
        <div class="search-bar flex items-center ml-6 flex-grow">
            <form action="{{ route('search') }}" method="GET" class="search-bar-form">
                <input type="text" name="query" placeholder="{{ __('Search...') }}" class="search-input flex-grow px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-color" />
                <select name="category" class="search-select px-4 py-2 border-l border-r border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-color">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->category_id }}">{{ __($category->name) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="search-button px-4 py-2 bg-accent-color text-white hover:bg-accent-color-dark focus:outline-none focus:ring-2 focus:ring-accent-color">
                    <i class="fas fa-search"></i>
                </button>
                <label for="exact_match" class="exact-match-button ml-2">
                    <input type="checkbox" id="exact_match" name="exact_match" value="1">
                    {{ __('Exact Match') }}
                </label>
            </form>
        </div>

        <!-- User Actions -->
        <div class="user-actions flex items-center space-x-4">
            <a href="{{ route('messages.index') }}" class="action-link" aria-label="{{ __('Messages') }}">
                <i class="fas fa-comments"></i>
            </a>

            <div style="position:relative; display:inline-block;">
                <a href="{{ route('notifications.index') }}" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span id="notification-count" style="display:none;"></span>
                </a>
            </div>

            @auth
                <!-- Novo Ícone de Leilões Seguidos -->
                <div style="position:relative; display:inline-block;">
                    <a href="{{ route('auctions.followed') }}" class="action-link" aria-label="{{ __('Followed Auctions') }}">
                        <i class="fas fa-heart"></i>
                        <!-- Opcional: Indicador de quantidade -->
                        @php
                            $followedCount = Auth::user()->followedAuctions()->count();
                        @endphp
                        @if($followedCount > 0)
                            <span class="followed-count badge">{{ $followedCount }}</span>
                        @endif
                    </a>
                </div>

                <a href="{{ route('profile.show') }}" class="action-link" aria-label="{{ __('Profile') }}">
                    <i class="fas fa-user"></i>
                </a>
                <a href="{{ route('auctions.create') }}" class="nav-link">{{ __('Create Auction') }}</a>
                <a href="{{ route('credits.add') }}" class="nav-link">{{ __('Credits: ') }}{{ Auth::user()->credits }}$</a>
            @else
                <div class="auth-links flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="nav-link">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="nav-link">{{ __('Register') }}</a>
                </div>
            @endauth

            <div class="language-switcher flex items-center space-x-1">
                <button onclick="changeLanguage('pt')" class="language-button {{ app()->getLocale() == 'pt' ? 'active' : '' }}">PT</button>
                <span>|</span>
                <button onclick="changeLanguage('en')" class="language-button {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</button>
            </div>

            <button id="theme-toggle" class="theme-toggle p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-color" aria-label="{{ __('Toggle Dark Mode') }}">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>
</header>
