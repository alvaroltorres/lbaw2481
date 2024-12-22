<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Auction;

class AuctionCanceledNotification extends Notification
{
    protected $auction;
    protected $reason;

    public function __construct(Auction $auction, $reason = null)
    {
        $this->auction = $auction;
        $this->reason  = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isOwner = ($notifiable->user_id === $this->auction->user_id);
        $title   = $this->auction->title;
        $reason  = $this->reason ?: __('No reason provided');

        $message = $isOwner
            ? __("Your auction \":title\" has been canceled. Reason: :reason", ['title' => $title, 'reason' => $reason])
            : __("The auction \":title\" you are following has been canceled. Reason: :reason", ['title' => $title, 'reason' => $reason]);


        return [
            'notification_type' => 'auction_canceled',
            'is_owner'          => $isOwner,
            'auction_id'        => $this->auction->auction_id,
            'auction_title'     => $title,
            'cancel_reason'     => $reason,
            'message'           => $message,
        ];
    }
}
