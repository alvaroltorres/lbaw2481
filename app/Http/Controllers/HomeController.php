<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use Carbon\Carbon;
use App\Models\Category;

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
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by');

        $auctions = Auction::query();

        if ($exactMatch) {
            $auctions->where(function ($q) use ($query) {
                $q->where('title', $query)
                ->orWhere('description', $query);
            });
        } else {
            $auctions->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        if ($category) {
            $auctions->where('category_id', $category);
        }

        if ($minPrice) {
            $auctions->where('current_price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $auctions->where('current_price', '<=', $maxPrice);
        }

        if ($status) {
            $auctions->where('status', $status);
        }

        if ($sortBy) {
            if ($sortBy == 'recent') {
                $auctions->orderBy('created_at', 'desc');
            } elseif ($sortBy == 'price_asc') {
                $auctions->orderBy('current_price', 'asc');
            } elseif ($sortBy == 'price_desc') {
                $auctions->orderBy('current_price', 'desc');
            }
        }

        $auctions = $auctions->get();
        $categories = Category::all();

        return view('auctions.search-results', compact('auctions', 'query', 'exactMatch', 'category', 'minPrice', 'maxPrice', 'status', 'sortBy', 'categories'));
    }
}
