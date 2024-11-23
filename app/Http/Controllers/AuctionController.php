<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
