<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Bid;
use Carbon\Carbon;

class NewBidNotification extends Notification
{
    protected $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isOwner       = ($notifiable->user_id === $this->bid->auction->user_id);
        $price         = $this->bid->price;
        $bidAmount     = number_format($price, 2, ',', '.');
        $auctionTitle  = $this->bid->auction->title;
        $bidderName    = $this->bid->user->fullname;

        $createdAt     = Carbon::parse($this->bid->time)->format('d/m/Y H:i');

        $message = $isOwner
            ? __("💸 New bid of €:amount on your auction \":title\" by :bidder at :time", [
                'amount'  => $bidAmount,
                'title'   => $auctionTitle,
                'bidder'  => $bidderName,
                'time'    => $createdAt
            ])
            : __("💸 A new bid of €:amount was placed on \":title\" by :bidder at :time (you are participating).", [
                'amount'  => $bidAmount,
                'title'   => $auctionTitle,
                'bidder'  => $bidderName,
                'time'    => $createdAt
            ]);
        
        return [
            'notification_type'     => 'new_bid',  // <--- IMPORTANTÍSSIMO
            'is_owner'              => $isOwner,
            'bid_id'                => $this->bid->bid_id,
            'auction_id'            => $this->bid->auction->auction_id,
            'bidder_id'             => $this->bid->user->user_id,
            'auction_title'         => $auctionTitle,
            'bid_amount'            => $price,
            'bid_amount_formatted'  => $bidAmount,
            'bidder_name'           => $bidderName,
            'time'                  => $createdAt,
            'message'               => $message,  // <--- Mensagem final
        ];
    }

}
