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
        $bidAmountFormatted = number_format($this->bid->price, 2, ',', '.');
        $auctionTitle = $this->bid->auction->title;
        $bidderName = $this->bid->user->fullname;
        $createdAt = Carbon::parse($this->bid->time)->format('d/m/Y H:i');

        $message = sprintf(
            'Nova oferta: €%s no leilão "%s" por %s em %s. Clique para ver detalhes e acompanhar o leilão!',
            $bidAmountFormatted,
            $auctionTitle,
            $bidderName,
            $createdAt
        );

        return [
            'bid_id' => $this->bid->bid_id,
            'auction_id' => $this->bid->auction->auction_id,
            'bidder_id' => $this->bid->user->user_id,
            'bidder_name' => $bidderName,
            'bid_amount' => $this->bid->price,
            'auction_title' => $auctionTitle,
            'message' => $message,
        ];
    }
}
