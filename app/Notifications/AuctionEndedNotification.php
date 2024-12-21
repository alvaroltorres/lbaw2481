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
            $message = "Parabéns! Você foi o vencedor do leilão \"{$title}\".";
        } else {
            $message = $isOwner
                ? "O seu leilão \"{$title}\" foi encerrado."
                : "O leilão \"{$title}\" em que você participou foi encerrado.";
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
