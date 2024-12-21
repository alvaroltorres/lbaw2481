<div class="profile-subnav">
    <div class="container">
        <ul class="profile-nav-list">
            <li><a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">{{ __('Profile') }}</a></li>
            <li><a href="{{ route('profile.biddingHistory') }}" class="{{ request()->routeIs('profile.biddingHistory') ? 'active' : '' }}">{{ __('Bidding History') }}</a></li>
            <li><a href="{{ route('profile.orders') }}" class="{{ request()->routeIs('profile.orders') ? 'active' : '' }}">{{ __('My Orders') }}</a></li>
            <li><a href="{{ route('profile.ratings') }}" class="{{ request()->routeIs('profile.ratings') ? 'active' : '' }}">{{ __('My Ratings') }}</a></li>
            <li><a href="{{ route('profile.myauctions') }}" class="{{ request()->routeIs('profile.myauctions') ? 'active' : '' }}">{{ __('My Auctions') }}</a></li>
            <li><a href="{{ route('profile.soldauctions') }}" class="{{ request()->routeIs('profile.soldauctions') ? 'active' : '' }}">{{ __('Sold Auctions') }}</a></li>
        </ul>
    </div>
</div>

<style>
    .profile-subnav {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 0;
    }

    .profile-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: space-around;
    }

    .profile-nav-list li {
        margin: 0;
    }

    .profile-nav-list a {
        text-decoration: none;
        color: #007bff;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .profile-nav-list a:hover,
    .profile-nav-list a.active {
        background-color: #007bff;
        color: #fff;
    }
</style>