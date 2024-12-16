<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Bid;

class NewBidNotification extends Notification
{
    protected $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function via($notifiable)
    {
        return ['database']; // Uses the database channel
    }

    public function toArray($notifiable)
    {
        return [
            'bid_id' => $this->bid->bid_id,
            'auction_id' => $this->bid->auction->auction_id,
            'bidder_id' => $this->bid->user->user_id,
            'bidder_name' => $this->bid->user->fullname,
            'bid_amount' => $this->bid->price,
            'auction_title' => $this->bid->auction->title,
            'message' => 'Uma nova oferta de €' . number_format($this->bid->price, 2, ',', '.') .
                ' foi feita no seu leilão: ' . $this->bid->auction->title .
                ' por ' . $this->bid->user->fullname . '.',
        ];
    }
}
