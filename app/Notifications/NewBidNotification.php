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

        // Valores para placeholders
        $price         = $this->bid->price;
        $bidAmount     = number_format($price, 2, ',', '.');
        $auctionTitle  = $this->bid->auction->title;
        $bidderName    = $this->bid->user->fullname;
        $createdAt     = Carbon::parse($this->bid->time)->format('d/m/Y H:i');

        // Mensagem final que será exibida no 'message'
        $message = $isOwner
            ? "💸 Novo lance de €{$bidAmount} no seu leilão \"{$auctionTitle}\" por {$bidderName} em {$createdAt}!"
            : "💸 Um novo lance de €{$bidAmount} foi dado no leilão \"{$auctionTitle}\" por {$bidderName} em {$createdAt} (você participa).";

        return [
            // Tipo de notificação
            'notification_type' => 'new_bid',

            // Dono ou não
            'is_owner'          => $isOwner,

            // IDs
            'bid_id'            => $this->bid->bid_id,
            'auction_id'        => $this->bid->auction->auction_id,
            'bidder_id'         => $this->bid->user->user_id,

            // Dados extras para placeholders no front-end
            'auction_title'        => $auctionTitle,
            'bid_amount'           => $price,          // <--- Agora temos 'bid_amount'...
            'bid_amount_formatted' => $bidAmount,      // <--- ...e 'bid_amount_formatted'
            'bidder_name'          => $bidderName,
            'time'                 => $createdAt,

            // A mensagem pronta
            'message'           => $message,
        ];
    }
}
