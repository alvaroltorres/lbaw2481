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
            ? "Foi determinado(a) um(a) vencedor(a) para o seu leilão \"{$title}\"! Vencedor: {$winnerName}."
            : "O leilão \"{$title}\" tem um(a) vencedor(a): {$winnerName}.";

        return [
            'notification_type' => 'auction_winner',
            'is_owner'          => $isOwner,
            'auction_id'        => $this->auction->auction_id,
            'auction_title'     => $title,
            'winner_id'         => $this->winner->user_id,
            'winner_name'       => $winnerName,
            'message'           => $message,
        ];
    }
}
