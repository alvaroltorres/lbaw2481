<!-- resources/views/partials/header.blade.php -->

<header class="header">
    <div class="container header__container">
        <div class="logo">
            <a href="/" aria-label="{{ __('BidZenith Home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="BidZenith Logo" class="h-8 w-8 mr-2">
                <span class="font-semibold text-xl">{{ __('BidZenith') }}</span>
            </a>
        </div>

        <div class="header__actions">
            <form action="{{ route('search') }}" method="get" class="search-form">
                <label for="search-input" class="visually-hidden">{{ __('Search') }}</label>
                <input type="text" id="search-input" name="query" placeholder="{{ __('Search...') }}" class="search-input">
            </form>
            <button id="theme-toggle" class="theme-toggle" aria-label="{{ __('Toggle Dark Mode') }}">
                <i class="fas fa-moon"></i>
            </button>
            <!-- Seletor de Idioma -->
            <div class="language-switcher ml-4">
                <select id="language-select" onchange="changeLanguage(this.value)" class="bg-gray-200 dark:bg-gray-600 p-2 rounded-md">
                    @foreach(config('app.locales') as $locale => $language)
                        <option value="{{ $locale }}" {{ app()->getLocale() == $locale ? 'selected' : '' }}>
                            {{ $language }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</header>
