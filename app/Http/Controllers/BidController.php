<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use App\Models\Notification; // Importar o modelo Notification
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
                'price' => 'O valor da oferta deve ser pelo menos ' .
                    number_format($auction->current_price + $auction->minimum_bid_increment, 2, ',', '.'),
            ]);
        }

        $bid = Bid::create([
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'price' => $validated['price'],
        ]);

        $auction->current_price = $bid->price;
        $auction->save();

        Log::info('Nova oferta feita', [
            'bid_id' => $bid->bid_id,
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'price' => $bid->price,
        ]);


        $currentUser = Auth::user();

        if ($auction->user_id !== $currentUser->user_id) {
            $auctionOwner = $auction->user;
            $auctionOwner->notify(new NewBidNotification($bid));
        }

        $otherBidderIds = $auction->bids()
            ->where('user_id', '!=', $currentUser->user_id)
            ->where('user_id', '!=', $auction->user_id)
            ->pluck('user_id')
            ->unique();

        $otherBidders = User::whereIn('user_id', $otherBidderIds)->get();

        foreach ($otherBidders as $user) {
            $user->notify(new NewBidNotification($bid));
        }
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Sua oferta foi feita com sucesso.');
    }
}
