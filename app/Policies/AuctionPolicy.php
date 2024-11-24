<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuctionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any auctions.
     */
    public function viewAny(?User $user)
    {
        // Anyone can view auctions
        return true;
    }

    /**
     * Determine whether the user can view a specific auction.
     */
    public function view(?User $user, Auction $auction)
    {
        // Anyone can view a specific auction
        return true;
    }

    /**
     * Determine whether the user can create auctions.
     */
    public function create(User $user)
    {
        // Only authenticated users can create auctions
        return $user !== null;
    }

    /**
     * Determine whether the user can update the auction.
     */
    public function update(User $user, Auction $auction)
    {
        // The user can update if they are the owner and the auction hasn't started or received any bids
        $hasStarted = now()->greaterThanOrEqualTo($auction->starting_date);
        $hasBids = $auction->bids()->exists();

        return $user->user_id === $auction->user_id && !$hasStarted && !$hasBids;
    }

    /**
     * Determine whether the user can delete the auction.
     */
    public function delete(User $user, Auction $auction)
    {
        // The user can delete if they are the owner and there are no bids
        $hasBids = $auction->bids()->exists();

        return $user->user_id === $auction->user_id && !$hasBids;
    }

    /**
     * Determine whether the user can place a bid on the auction.
     */
    public function placeBid(User $user, Auction $auction)
    {
        // The user can place a bid if they are not the owner and the auction is active
        $now = now();
        $isActive = $auction->starting_date <= $now && $now <= $auction->ending_date;

        return $user->user_id !== $auction->user_id && $isActive;
    }

    /**
     * Determine whether the user can view the bidding history.
     */
    public function viewBiddingHistory(?User $user, Auction $auction)
    {
        // Anyone can view the bidding history
        return true;
    }
}
