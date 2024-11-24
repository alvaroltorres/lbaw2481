<?php

namespace App\Policies;

use App\Models\Bid;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BidPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a bid.
     */
    public function create(User $user)
    {
        // Only authenticated users can create bids
        return $user !== null;
    }

    /**
     * Determine whether the user can delete a bid.
     */
    public function delete(User $user, Bid $bid)
    {
        // Only administrators can delete bids
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view a bid.
     */
    public function view(?User $user, Bid $bid)
    {
        // Anyone can view bids
        return true;
    }

    /**
     * Determine whether the user can view any bids.
     */
    public function viewAny(?User $user)
    {
        // Anyone can view bids
        return true;
    }
}
