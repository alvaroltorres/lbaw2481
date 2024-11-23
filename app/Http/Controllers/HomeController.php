<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()  {
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

    public function search(Request $request)
    {
        $query = $request->input('query');
        $exactMatch = $request->input('exact_match', false);
        $category = $request->input('category');

        $auctions = Auction::query();

        if ($exactMatch) {
            $auctions->where('title', $query)
                ->orWhere('description', $query);
        } else {
            $auctions->where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
        }

        if ($category) {
            $auctions->where('category_id', $category);
        }

        $auctions = $auctions->get();

        return view('auctions.search-results', compact('auctions', 'query', 'exactMatch', 'category'));
    }
}
