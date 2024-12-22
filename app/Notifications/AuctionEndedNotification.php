<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Auction;

class AuctionEndedNotification extends Notification
{
    protected $auction;

    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isOwner = ($notifiable->user_id === $this->auction->user_id);
        $title   = $this->auction->title;

        // se o winner_id for igual ao user_id do usuário autenticado
        if ($this->auction->winner_id === $notifiable->user_id) {
            $message = __("Congratulations! You won the auction \":title\".", ['title' => $title]);
        } else {
            $message = $isOwner
                ? __("Your auction \":title\" has ended.", ['title' => $title])
                : __("The auction \":title\" you participated in has ended.", ['title' => $title]);
        }

        return [
            'notification_type' => 'auction_ended',
            'is_owner'          => $isOwner,
            'auction_id'        => $this->auction->auction_id,
            'auction_title'     => $title,
            'message'           => $message,
        ];
    }
}
