<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;
use App\Models\Bid;
use App\Models\Auction;
use App\Models\User;

class Notification extends DatabaseNotification
{
    public function getBidAttribute()
    {
        if (isset($this->data['bid_id'])) {
            return Bid::find($this->data['bid_id']);
        }
        return null;
    }

    public function getAuctionAttribute()
    {
        if (isset($this->data['auction_id'])) {
            return Auction::find($this->data['auction_id']);
        }
        return null;
    }

    public function getBidderAttribute()
    {
        if (isset($this->data['bidder_id'])) {
            return User::find($this->data['bidder_id']);
        }
        return null;
    }
}
