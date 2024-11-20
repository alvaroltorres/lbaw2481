<header class="header">
    <div class="container header__container flex items-center justify-between">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('home') }}" aria-label="{{ __('BidZenith Home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="BidZenith Logo" class="logo-img">
            </a>
        </div>

        <!-- search bar -->
        <div class="search-bar flex items-center ml-6 flex-grow">
            <form action="{{ route('search') }}" method="GET" class="flex w-full">
                <input type="text" name="query" placeholder="{{ __('Buscar...') }}" class="search-input flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-color dark:bg-gray-700 dark:text-white">
                <select name="category" class="search-select px-4 py-2 border-t border-b border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-color dark:bg-gray-700 dark:text-white">
                    <option value="">{{ __('Todas as Categorias') }}</option>
                    @foreach(config('categories') as $slug => $name)
                        <option value="{{ $slug }}">{{ $name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="search-button px-4 py-2 bg-accent-color text-white hover:bg-accent-color-dark focus:outline-none focus:ring-2 focus:ring-accent-color">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- user actions -->
        <div class="user-actions flex items-center space-x-4">
            <!-- Símbolo de Chats -->
            <a href="{{ route('messages') }}" class="action-link" aria-label="{{ __('Mensagens') }}">
                <i class="fas fa-comments"></i>
            </a>
            <!-- notificações -->
            <a href="{{ route('notifications.index') }}" class="action-link relative" aria-label="{{ __('Notificações') }}">
                <i class="fas fa-bell"></i>
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="notification-count absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

            @auth
                <!-- profile -->
                <a href="{{ route('profile.show') }}" class="action-link" aria-label="{{ __('Perfil') }}">
                    <i class="fas fa-user"></i>
                </a>
            @else
                <!-- login/register -->
                <div class="auth-links flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="nav-link">
                        {{ __('Entrar') }}
                    </a>
                    <a href="{{ route('register') }}" class="nav-link">
                        {{ __('Cadastrar') }}
                    </a>
                </div>
            @endauth

            <!-- language switcher -->
            <div class="language-switcher flex items-center space-x-1">
                <button onclick="changeLanguage('pt')" class="language-button {{ app()->getLocale() == 'pt' ? 'active' : '' }}">PT</button>
                <span>|</span>
                <button onclick="changeLanguage('en')" class="language-button {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</button>
            </div>

            <!-- escuro/claro -->
            <button id="theme-toggle" class="theme-toggle p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-color" aria-label="{{ __('Alternar Modo Escuro') }}">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>
</header>
