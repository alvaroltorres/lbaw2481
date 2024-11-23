<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        // Validate the price input
        $validated = $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        // Check if the bid amount is valid
        if ($validated['price'] < $auction->current_price + $auction->minimum_bid_increment) {
            throw ValidationException::withMessages([
                'price' => 'The bid price must be at least ' .
                    ($auction->current_price + $auction->minimum_bid_increment),
            ]);
        }

        // Create the bid with the correct auction_id and user_id
        $bid = Bid::create([
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'price' => $validated['price'],
        ]);

        // Update the current price of the auction
        $auction->current_price = $bid->price;
        $auction->save();

        // Add debugging information
        Log::info('New bid placed', [
            'bid_id' => $bid->bid_id,
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'price' => $bid->price,
        ]);

        // Redirect to the auction page after placing the bid
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Your bid has been placed successfully.');
    }
}
