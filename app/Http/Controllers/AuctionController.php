<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\AuctionRequest;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeauctions = Auction::where('status', 'Open')->get();
        return view('auctions.index', compact('activeauctions'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        // Pass the categories to the view
        return view('auctions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuctionRequest $request)
    {
        {
            $validated = $request->validated();

            $auction = new Auction($validated);
            $auction->user_id = Auth::id();  // Assign the logged-in user's ID
            $auction->current_price = $request->starting_price;  // Set current price equal to the starting price
            $auction->save();


            return redirect()
                ->route('auctions.show', [$auction])
                ->with('success', 'Auction is submitted!'
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        return view('auctions.show', ['auction' => $auction]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $exactMatch = $request->input('exact_match', false);

        if ($exactMatch) {
            $auctions = Auction::where('title', $query)
                ->orWhere('description', $query)
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', $query);
                })
                ->get();
        } else {
            $auctions = Auction::where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->get();
        }

        return view('auctions.search-results', compact('auctions', 'query', 'exactMatch'));
    }
}
