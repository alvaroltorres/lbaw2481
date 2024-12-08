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
    public function index(Request $request)
    {
        $query = Auction::query();

        // Aplica filtros
        if ($request->has('category') && $request->category) {
            $category = Category::find($request->category);

            if ($category) {
                $subcategories = $this->getSubcategories($category->category_id);

                $query->whereIn('category_id', $subcategories);
            }
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('starting_price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('starting_price', '<=', $request->max_price);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $activeAuctions = $query->paginate(10);

        $categories = Category::all();

        return view('auctions.index', compact('activeAuctions', 'categories'));
    }

    /**
     * Recupera todas as subcategorias de uma categoria.
     */
    private function getSubcategories($categoryId)
    {
        $categories = Category::where('parent_id', $categoryId)->pluck('category_id')->toArray();
        $allCategories = array_merge([$categoryId], $categories);

        foreach ($categories as $subcategoryId) {
            $allCategories = array_merge($allCategories, $this->getSubcategories($subcategoryId));
        }

        return $allCategories;
    }

    public function create()
    {
        $categories = Category::all();
        return view('auctions.create', compact('categories'));
    }

    public function store(AuctionRequest $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'required|numeric|min:0',
            //'minimum_bid_increment' => 'required|numeric|min:0',
            'starting_date' => 'required|date',
            'ending_date' => 'required|date|after:starting_date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $auction = new Auction($validated);
        $auction->user_id = Auth::id();
        $auction->current_price = $request->starting_price;
        $auction->save();

        return redirect()->route('auctions.show', $auction)->with('success', 'Auction created successfully!');
    }

    public function show(Auction $auction)
    {
        return view('auctions.show', compact('auction'));
    }

    public function edit(Auction $auction)
    {
        if ($auction->user_id !== Auth::id()) {
            return redirect()->route('auctions.show', $auction)->with('error', 'You do not have permission to edit this auction.');
        }

        $categories = Category::all();
        return view('auctions.edit', compact('auction', 'categories'));
    }

    public function update(AuctionRequest $request, Auction $auction)
    {
        if ($auction->user_id !== Auth::id()) {
            return redirect()->route('auctions.show', $auction)->with('error', 'You do not have permission to edit this auction.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'required|numeric|min:0',
            //'minimum_bid_increment' => 'required|numeric|min:0',
            'starting_date' => 'required|date',
            'ending_date' => 'required|date|after:starting_date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($auction->update($validated)) {
            return redirect()->route('auctions.show', $auction)->with('success', 'Auction updated successfully!');
        } else {
            return redirect()->route('auctions.edit', $auction)->with('error', 'An error occurred while updating the auction. Please try again.');
        }
    }

    public function destroy(Auction $auction)
    {
        if ($auction->user_id !== Auth::id()) {
            return redirect()->route('auctions.show', $auction)->with('error', 'You do not have permission to delete this auction.');
        }

        $auction->delete();

        return redirect()->route('auctions.index')->with('success', 'Auction deleted successfully!');
    }

    public function biddingHistory(Auction $auction)
    {
        $bids = $auction->bids()->orderBy('time', 'desc')->get();

        return view('auctions.bidding_history', compact('auction', 'bids'));
    }

    public function followed()
    {
        $user = Auth::user();
        $followedAuctions = $user->followingAuctions;

        return view('auctions.followed', compact('followedAuctions'));
    }
}
?>
