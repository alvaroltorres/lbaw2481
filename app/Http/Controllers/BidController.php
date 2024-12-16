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
        // Validate the incoming price (bid amount)
        $validated = $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        // Check if the bid is higher than the current price + minimum bid increment
        if ($validated['price'] < $auction->current_price + $auction->minimum_bid_increment) {
            throw ValidationException::withMessages([
                'price' => 'O valor da oferta deve ser pelo menos ' .
                    number_format($auction->current_price + $auction->minimum_bid_increment, 2, ',', '.'),
            ]);
        }

        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user has enough credits to make the bid
        if ($currentUser->credits < $validated['price']) {
            return back()->withErrors([
                'price' => 'Você não tem créditos suficientes para fazer esta oferta.',
            ]);
        }

        // Get the previous highest bid (if any)
        $previousBid = $auction->bids()->orderBy('price', 'desc')->first();

        // Create the new bid
        $bid = Bid::create([
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'price' => $validated['price'],
        ]);

        // Update the auction's current price
        $auction->current_price = $bid->price;
        $auction->save();

        // Deduct the credits from the current user
        $currentUser->credits -= $validated['price'];
        $currentUser->save();

        // If there was a previous bid, refund the previous bidder
        if ($previousBid) {
            $previousBidder = User::find($previousBid->user_id);

            // Refund the previous bidder
            $previousBidder->credits += $previousBid->price;
            $previousBidder->save();

            // Optionally log the refund action
            Log::info('Previous bidder refunded', [
                'user_id' => $previousBidder->user_id,
                'auction_id' => $auction->auction_id,
                'previous_bid' => $previousBid->price,
            ]);
        }

        // Log the new bid
        Log::info('Nova oferta feita', [
            'bid_id' => $bid->bid_id,
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'price' => $bid->price,
        ]);

        // Notify the auction owner if it's not the current user
        //if ($auction->user_id !== $currentUser->user_id) {
        //    $auctionOwner = $auction->user;
        //    $auctionOwner->notify(new NewBidNotification($bid));
        //}

        // Get other users who have placed bids on the auction
        $otherBidderIds = $auction->bids()
            ->where('user_id', '!=', $auction->user_id)
            ->pluck('user_id')
            ->unique();

        $otherBidders = User::whereIn('user_id', $otherBidderIds)->get();

        // Notify other bidders
        //foreach ($otherBidders as $user) {
        //    $user->notify(new NewBidNotification($bid));
        //}

        // Redirect the user back to the auction show page with a success message
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Sua oferta foi feita com sucesso.');
    }


}
