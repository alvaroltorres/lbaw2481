<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BidController extends Controller
{
    public function store(Request $request)
    {
        // Validate the price input
        $validated = $request->validate([
            'price' => 'required|numeric|min:0.01',
            'auction_id' => 'required|exists:auction,auction_id'
        ]);

        $auction = Auction::findOrFail($request->auction_id);

        if ($validated['price'] < $auction->current_price + $auction->minimum_bid_increment) {
            throw ValidationException::withMessages([
                'price' => 'The bid price must be higher than the current auction price plus minimum bid increment of ' .
                    ($auction->current_price + $auction->minimum_bid_increment),
            ]);
        }


        // Create the bid with the correct auction_id and user_id
        Bid::create([
            'auction_id' => $request->auction_id,  // Access auction_id from the Auction model
            'user_id' => Auth::id(), // Get the authenticated user's ID
            'price' => $validated['price'],
        ]);

        // Redirect to the auction page after placing the bid
        return redirect()->route('auctions.show', ['auction' => $request->auction_id]);
    }
}
