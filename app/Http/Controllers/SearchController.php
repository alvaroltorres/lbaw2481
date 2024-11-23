<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');

        $auctions = Auction::query()
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('query') . '%')
                      ->orWhere('description', 'like', '%' . $request->input('query') . '%');
            })
            ->get();

        return view('search.results', compact('auctions', 'query', 'category'));
    }
}