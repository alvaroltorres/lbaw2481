<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// Import Models and Policies
use App\Models\Auction;
use App\Policies\AuctionPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Bid;
use App\Policies\BidPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Auction::class => AuctionPolicy::class,
        User::class => UserPolicy::class,
        Bid::class => BidPolicy::class,
        // Add other policies here
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
