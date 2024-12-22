<?php

namespace App\Http\Controllers;



use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use App\Notifications\NewBidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {

        $validated = $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        if ($validated['price'] < $auction->current_price + $auction->minimum_bid_increment) {
            throw ValidationException::withMessages([
                'price' => 'The bid must be at least ' .
                    number_format($auction->current_price + $auction->minimum_bid_increment, 2, ',', '.'),
            ]);
        }

        $currentUser = Auth::user();

        if ($currentUser->credits < $validated['price']) {
            return back()->withErrors([
                'price' => 'You do not have enough credits to make this bid.',
            ]);
        }

        $previousBid = $auction->bids()->orderBy('price', 'desc')->first();

        $bid = Bid::create([
            'auction_id' => $auction->auction_id,
            'user_id' => $currentUser->user_id,
            'price' => $validated['price'],
        ]);

        // Atualiza o preço atual do leilão
        $auction->current_price = $bid->price;
        $auction->save();

        // Debita os créditos do usuário atual
        $currentUser->credits -= $validated['price'];
        $currentUser->save();

        // Reembolsa o lance anterior, se houver
        if ($previousBid) {
            $previousBidder = User::find($previousBid->user_id);
            $previousBidder->credits += $previousBid->price;
            $previousBidder->save();

            Log::info('Previous bidder refunded', [
                'user_id'     => $previousBidder->user_id,
                'auction_id'  => $auction->auction_id,
                'previous_bid'=> $previousBid->price,
            ]);
        }

        Log::info('Nova oferta feita', [
            'bid_id'     => $bid->bid_id,
            'auction_id' => $auction->auction_id,
            'user_id'    => $currentUser->user_id,
            'price'      => $bid->price,
        ]);

        // Notificar o owner do leilão (se não for o próprio user que deu o lance)
        if ($auction->user_id !== $currentUser->user_id) {
            $auctionOwner = $auction->user;
            if ($auctionOwner) {
                $auctionOwner->notify(new NewBidNotification($bid));
            }
        }

        // Notificar outros useers que deram lances anteriormente (exceto o atual e o dono)
        $otherBidderIds = $auction->bids()
            ->where('user_id', '!=', $auction->user_id)
            ->where('user_id', '!=', $currentUser->user_id)
            ->pluck('user_id')
            ->unique();

        $otherBidders = User::whereIn('user_id', $otherBidderIds)->get();
        foreach ($otherBidders as $user) {
            $user->notify(new NewBidNotification($bid));
        }

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Your bid has been successfully placed.');
    }
}
