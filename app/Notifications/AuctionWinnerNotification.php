<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Auction;
use App\Models\User;

class AuctionWinnerNotification extends Notification
{
    protected $auction;
    protected $winner;

    public function __construct(Auction $auction, User $winner)
    {
        $this->auction = $auction;
        $this->winner  = $winner;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isOwner = ($notifiable->user_id === $this->auction->user_id);
        $title   = $this->auction->title;
        $winnerName = $this->winner->fullname ?: $this->winner->username;

        $message = $isOwner
            ? __("A winner has been determined for your auction \":title\"! Winner: :winner.", [
                'title'  => $title,
                'winner' => $winnerName
            ])
            : __("The auction \":title\" now has a winner: :winner.", [
                'title'  => $title,
                'winner' => $winnerName
            ]);
        return [
            'notification_type' => 'auction_winner', // <--- KEY
            'is_owner'          => $isOwner,
            'auction_id'        => $this->auction->auction_id,
            'auction_title'     => $title,
            'winner_id'         => $this->winner->user_id,
            'winner_name'       => $winnerName,
            'message'           => $message, // <--- Mensagem final
        ];
    }

}
