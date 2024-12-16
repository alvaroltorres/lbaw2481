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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0&icon_names=search" />
    <!-- CSS e JS Compilados pelo Vite -->
    <link rel="stylesheet" href="{{asset("css/app.css")}}">
    <script src="{{asset('js/main.js')}}" async></script>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
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
            <form action="{{route('search')}}" method="GET" class="search-bar-form">
                <input type="text" name="query" placeholder="{{ __('Search...') }}" class="search-input flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-color dark:bg-gray-700 dark:text-white">
                <select name="category" class="search-select px-4 py-2 border-t border-b border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-color dark:bg-gray-700 dark:text-white">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <!-- Exact match checkbox -->
                <button type="submit" class="search-button px-4 py-2 bg-accent-color text-white hover:bg-accent-color-dark focus:outline-none focus:ring-2 focus:ring-accent-color">
                    <span class="material-symbols-outlined" style="scale: 1.7; margin-top: 0.2rem">search</span>
                </button>
                <label for="exact_match" class="exact-match-button">
                    <input type="checkbox" id="exact_match" name="exact_match" value="1">
                    {{ __('Exact Match') }}
                </label>
            </form>
        </div>

        <!-- user actions -->
        <div class="user-actions flex items-center space-x-4">
            <!-- Símbolo de Chats -->
            <a href="{{ route('messages') }}" class="action-link" aria-label="{{ __('Mensagens') }}">
                <i class="fas fa-comments"></i>
            </a>
            <!-- APAGAR PARA FUNCIONAR -->
             <!-- Notificações -->


            @auth
                <!-- profile -->
                <a href="{{ route('profile.show') }}" class="action-link" aria-label="{{ __('Perfil') }}">
                    <i class="fas fa-user"></i>
                </a>
                <a href="{{ route('auctions.create') }}" class="nav-link">
                    {{ __('Create Auction') }}
                </a>
                <!-- Link to Add Credits page -->
                <a href="{{ route('credits.add') }}" class="nav-link">
                    {{ __('Credits : ') }}
                    {{ Auth::user()->credits }}€
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

<!-- Scripts -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/notifications.js') }}"></script>

@yield('scripts')
</body>
</html>
