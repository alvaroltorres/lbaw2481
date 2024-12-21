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
        if (auth()->user()->is_admin) {
            abort(403, 'Administradores não podem licitar em leilões.');
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        if ($validated['price'] < $auction->current_price + $auction->minimum_bid_increment) {
            throw ValidationException::withMessages([
                'price' => 'O valor da oferta deve ser pelo menos ' .
                    number_format($auction->current_price + $auction->minimum_bid_increment, 2, ',', '.'),
            ]);
        }

        $currentUser = Auth::user();

        if ($currentUser->credits < $validated['price']) {
            return back()->withErrors([
                'price' => 'Você não tem créditos suficientes para fazer esta oferta.',
            ]);
        }

        $previousBid = $auction->bids()->orderBy('price', 'desc')->first();

        $bid = Bid::create([
            'auction_id' => $auction->auction_id,
            'user_id' => $currentUser->user_id,
            'price' => $validated['price'],
        ]);

        $auction->current_price = $bid->price;
        $auction->save();

        $currentUser->credits -= $validated['price'];
        $currentUser->save();

        if ($previousBid) {
            $previousBidder = User::find($previousBid->user_id);
            $previousBidder->credits += $previousBid->price;
            $previousBidder->save();

            Log::info('Previous bidder refunded', [
                'user_id' => $previousBidder->user_id,
                'auction_id' => $auction->auction_id,
                'previous_bid' => $previousBid->price,
            ]);
        }

        Log::info('Nova oferta feita', [
            'bid_id' => $bid->bid_id,
            'auction_id' => $auction->auction_id,
            'user_id' => $currentUser->user_id,
            'price' => $bid->price,
        ]);

        // Notificar o dono do leilão (se não for o próprio usuário)
        if ($auction->user_id !== $currentUser->user_id) {
            $auctionOwner = $auction->user;
            if ($auctionOwner) {
                $auctionOwner->notify(new NewBidNotification($bid));
            }
        }

        // Notificar outros usuários que deram lances anteriormente (exceto o atual e o dono)
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
            ->with('success', 'Sua oferta foi feita com sucesso!');
    }
}
