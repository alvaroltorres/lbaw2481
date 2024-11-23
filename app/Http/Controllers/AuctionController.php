<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    /**
     * Display a listing of active auctions.
     */
    public function index()
    {
        // Retrieve all active auctions
        $activeAuctions = Auction::where('status', 'Active')->get();
        return view('auctions.index', compact('activeAuctions'));
    }

    /**
     * Show the form for creating a new auction.
     */
    public function create()
    {
        // Get all categories to populate the category select field
        $categories = Category::all();
        return view('auctions.create', compact('categories'));
    }

    /**
     * Store a newly created auction in storage.
     */
    public function store(AuctionRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Create a new auction with validated data
        $auction = new Auction($validated);
        $auction->user_id = Auth::id();  // Assign the logged-in user's ID
        $auction->current_price = $request->starting_price;  // Set current price equal to the starting price
        $auction->save();

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Auction created successfully!');
    }

    /**
     * Display the specified auction.
     */
    public function show(Auction $auction)
    {
        // Show the auction details
        return view('auctions.show', compact('auction'));
    }

    /**
     * Show the form for editing the specified auction.
     */
    public function edit(Auction $auction)
    {
        // Check if the authenticated user is the owner of the auction
        if ($auction->user_id !== Auth::id()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to edit this auction.');
        }

        // Get all categories for the category select field
        $categories = Category::all();
        return view('auctions.edit', compact('auction', 'categories'));
    }

    /**
     * Update the specified auction in storage.
     */
    public function update(AuctionRequest $request, Auction $auction)
    {
        // Check if the authenticated user is the owner of the auction
        if ($auction->user_id !== Auth::id()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to edit this auction.');
        }

        // Validate the request data
        $validated = $request->validated();

        // Update the auction with validated data
        if ($auction->update($validated)) {
            return redirect()
                ->route('auctions.show', $auction)
                ->with('success', 'Auction updated successfully!');
        } else {
            return redirect()
                ->route('auctions.edit', $auction)
                ->with('error', 'An error occurred while updating the auction. Please try again.');
        }
    }

    /**
     * Remove the specified auction from storage.
     */
    public function destroy(Auction $auction)
    {
        // Check if the authenticated user is the owner of the auction
        if ($auction->user_id !== Auth::id()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to delete this auction.');
        }

        // Delete the auction
        $auction->delete();

        return redirect()->route('auctions.index')
            ->with('success', 'Auction deleted successfully!');
    }

    /**
     * Display the bidding history of an auction.
     */
    public function biddingHistory(Auction $auction)
    {
        // Retrieve all bids for the auction, ordered by time descending
        $bids = $auction->bids()->orderBy('time', 'desc')->get();

        return view('auctions.bidding_history', compact('auction', 'bids'));
    }

    /**
     * Display auctions followed by the authenticated user.
     */
    public function followed()
    {
        $user = Auth::user();
        // Assuming there is a relationship 'followingAuctions' on the User model
        $followedAuctions = $user->followingAuctions;

        return view('auctions.followed', compact('followedAuctions'));
    }

    // Additional methods like search can be added here
}
