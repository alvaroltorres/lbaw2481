<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Auction;
use Carbon\Carbon;

class AuctionEndingNotification extends Notification
{
    protected $auction;
    protected $minutesLeft;

    public function __construct(Auction $auction, int $minutesLeft = 30)
    {
        $this->auction = $auction;
        $this->minutesLeft = $minutesLeft;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isOwner = ($notifiable->user_id === $this->auction->user_id);

        $auctionTitle = $this->auction->title;
        $endTime      = $this->auction->ending_date
            ? $this->auction->ending_date->format('d/m/Y H:i')
            : __('(unknown)');

        $message = $isOwner
            ? __("⚠️ Your auction \":title\" will end in :minutes minutes (scheduled for :end).", [
                'title'   => $auctionTitle,
                'minutes' => $this->minutesLeft,
                'end'     => $endTime
            ])
            : __("⚠️ The auction \":title\" you are participating in will end in :minutes minutes (scheduled for :end).", [
                'title'   => $auctionTitle,
                'minutes' => $this->minutesLeft,
                'end'     => $endTime
            ]);

        return [
            'notification_type' => 'auction_ending',
            'is_owner'          => $isOwner,
            'auction_id'        => $this->auction->auction_id,
            'auction_title'     => $auctionTitle,
            'end_time'          => $endTime,
            'minutes_left'      => $this->minutesLeft,
            'message'           => $message,
        ];
    }
}
