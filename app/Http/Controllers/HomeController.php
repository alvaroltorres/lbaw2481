<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch active auctions (those that have started and haven't ended yet)
        $activeAuctions = Auction::where('starting_date', '<=', Carbon::now())
            ->where('ending_date', '>', Carbon::now())
            ->get();

        // Fetch upcoming auctions (those that haven't started yet)
        $upcomingAuctions = Auction::where('starting_date', '>', Carbon::now())
            ->get();

        // Pass the variables to the view
        return view('home', compact('activeAuctions', 'upcomingAuctions'));
    }
}
