<header>
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="logo">BidZenith</a>
            <ul class="nav-links">
                <li><a href="{{ route('auctions.index') }}">Auctions</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-secondary">Sign Up</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</header>