<!-- Header Section -->
<header class="header">
    <div class="container-2 header__container">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('home') }}" aria-label="{{ __('BidZenith Home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="BidZenith Logo" class="logo-img">
            </a>
        </div>

        <!-- Search Bar -->
        <div class="search-bar flex items-center ml-6 flex-grow">
            <form action="{{ route('search') }}" method="GET" class="search-bar-form flex items-center w-full">
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
                <label for="exact_match" class="exact-match-button ml-2 flex items-center">
                    <input type="checkbox" id="exact_match" name="exact_match" value="1" class="mr-1">
                    {{ __('Exact Match') }}
                </label>
            </form>
        </div>

        <!-- User Actions -->
        <div class="user-actions flex items-center space-x-4">
            <a href="{{ route('messages.index') }}" class="action-link" aria-label="{{ __('Messages') }}">
                <i class="fas fa-comments"></i>
            </a>

            <div class="user-notifications relative inline-block">
                <a href="{{ route('notifications.index') }}" class="action-link" aria-label="{{ __('Notifications') }}">
                    <i class="fas fa-bell"></i>
                </a>
            </div>

            @auth
                <!-- Novo Ícone de Leilões Seguidos -->
                <div class="relative inline-block">
                    <a href="{{ route('auctions.followed') }}" class="action-link" aria-label="{{ __('Followed Auctions') }}">
                        <i class="fas fa-heart"></i>
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
        </div>
    </div>

    <!-- Subheader para Perfil -->
    @if(request()->is('profile*'))
            <div class="container">
                <ul class="profile-nav-list flex space-x-4">
                    <li><a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">{{ __('Profile') }}</a></li>
                    <li><a href="{{ route('profile.biddingHistory') }}" class="{{ request()->routeIs('profile.biddingHistory') ? 'active' : '' }}">{{ __('Bidding History') }}</a></li>
                    <li><a href="{{ route('profile.orders') }}" class="{{ request()->routeIs('profile.orders') ? 'active' : '' }}">{{ __('My Orders') }}</a></li>
                    <li><a href="{{ route('profile.ratings') }}" class="{{ request()->routeIs('profile.ratings') ? 'active' : '' }}">{{ __('My Ratings') }}</a></li>
                    <li><a href="{{ route('profile.myauctions') }}" class="{{ request()->routeIs('profile.myauctions') ? 'active' : '' }}">{{ __('My Auctions') }}</a></li>
                    <li><a href="{{ route('profile.soldauctions') }}" class="{{ request()->routeIs('profile.soldauctions') ? 'active' : '' }}">{{ __('Sold Auctions') }}</a></li>
                </ul>
            </div>
    @endif
</header>
<!-- css -->
<style>
    .container-2 {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 7rem 0 7rem;

    }
</style>