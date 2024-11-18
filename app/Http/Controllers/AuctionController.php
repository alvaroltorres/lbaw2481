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
        $auctions = Auction::where('status', 'Open')->get();
        return view('auctions.index', compact('auctions'));
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
        //
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
}
