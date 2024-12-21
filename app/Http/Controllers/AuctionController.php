<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Category;
use App\Models\FollowAuction;
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
            $query->where('current_price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('current_price', '<=', $request->max_price);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Apply sorting filters
        if ($request->has('sort_by')) {
            if ($request->sort_by == 'recent') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort_by == 'price_asc') {
                $query->orderBy('current_price', 'asc');
            } elseif ($request->sort_by == 'price_desc') {
                $query->orderBy('current_price', 'desc');
            }
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
            'category_id' => 'required|exists:Category,category_id',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'required|numeric|min:0',
            'minimum_bid_increment' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Sold,Unsold,Upcoming,Closed',
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

        // In your code where you're checking the follow status, modify the query like this:

        $isFollowed = FollowAuction::where('follow_auctions.user_id', Auth::id())  // Specify follow_auctions.user_id
        ->where('follow_auctions.auction_id', $auction->auction_id)  // Specify follow_auctions.auction_id
        ->exists();

        return view('auctions.show', compact('auction', 'isFollowed'));
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
            'category_id' => 'required|exists:Category,category_id',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'required|numeric|min:0',
            //'minimum_bid_increment' => 'required|numeric|min:0',
            'starting_date' => 'required|date',
            'ending_date' => 'required|date|after:starting_date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Active,Sold,Unsold,Upcoming,Closed',
        ]);

        if ($auction->update($validated)) {
            return redirect()->route('auctions.show', $auction)->with('success', 'Auction updated successfully!');
        } else {
            return redirect()->route('auctions.edit', $auction)->with('error', 'An error occurred while updating the auction. Please try again.');
        }
    }

    public function followAuction(Request $request, $auction_id)
    {
        // Get the currently authenticated user's ID
        $user_id = Auth::id();

        // Check if the user is already following this auction
        $isFollowing = FollowAuction::where('user_id', $user_id)
            ->where('auction_id', $auction_id)
            ->exists();

        // If not already following, create a new follow entry
        if (!$isFollowing) {
            FollowAuction::create([
                'user_id' => $user_id,
                'auction_id' => $auction_id,
            ]);

            return back()->with('status', 'Auction followed successfully.');
        } else {
            return back()->with('status', 'You are already following this auction.');
        }
    }

    public function unfollowAuction(Request $request, $auction_id)
    {
        // Get the currently authenticated user's ID
        $user_id = Auth::id();

        // Check if the user is following this auction
        $isFollowing = FollowAuction::where('user_id', $user_id)
            ->where('auction_id', $auction_id)
            ->exists();

        // If the user is following, delete the follow entry
        if ($isFollowing) {
            FollowAuction::where('user_id', $user_id)
                ->where('auction_id', $auction_id)
                ->delete();

            return back()->with('status', 'Auction unfollowed successfully.');
        } else {
            return back()->with('status', 'You are not following this auction.');
        }
    }


    public function followedAuctions()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Fetch the auctions the user is following
        $auctions = $user->followedAuctions()->get();

        // Pass the auctions to the view
        return view('auctions.followed', compact('auctions'));
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

    public function biddingHistoryForUser()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve all auctions where the user has placed a bid
        $auctions = Auction::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->get();

        $bids = Bid::where('user_id', $user->user_id)->orderBy('time', 'desc')->get();
        // Pass the auctions to the view
        return view('profile.bidding_history', compact('user', 'auctions', 'bids'));
    }


    public function followed()
    {
        $user = Auth::user();
        $followedAuctions = $user->followingAuctions;

        return view('auctions.followed', compact('followedAuctions'));
    }
    /**
     * Exibe a página para encerrar o leilão e selecionar o vencedor.
     */
    public function showEndAuction(Auction $auction)
    {
        // Verifica se o usuário autenticado é o vendedor do leilão
        if (Auth::id() !== $auction->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Obtém todos os lances do leilão
        $bids = $auction->bids()->with('user')->orderBy('price', 'desc')->get();

        return view('auctions.end_auction', compact('auction', 'bids'));
    }

    /**
     * Processa o encerramento do leilão e define o vencedor.
     */
    public function endAuction(Request $request, Auction $auction)
    {
        // Verifica se o usuário autenticado é o vendedor do leilão
        if (Auth::id() !== $auction->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Valida o input
        $request->validate([
            'winner_id' => 'required|exists:users,id',
        ]);

        // Define o vencedor
        $winner = $auction->bids()->where('user_id', $request->winner_id)->first();

        if (!$winner) {
            return redirect()->back()->with('error', 'Bidder not found.');
        }

        // Atualiza o status do leilão e define o vencedor
        $auction->status = 'ended';
        $auction->winner_id = $winner->user_id; // Certifique-se de ter uma coluna 'winner_id' na tabela de leilões
        $auction->save();

        // Opcional: Notificar o vencedor e o vendedor sobre o encerramento do leilão

        return redirect()->route('auctions.show', $auction)->with('success', 'Auction ended successfully and winner has been selected.');
    }
}
?>
