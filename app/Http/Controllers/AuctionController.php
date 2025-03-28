<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Transaction;
use App\Models\Order;

use App\Http\Requests\AuctionRequest;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Category;
use App\Models\FollowAuction;
use App\Models\User;
use App\Notifications\AuctionCanceledNotification;
use App\Notifications\AuctionEndedNotification;
use App\Notifications\AuctionEndingNotification;
use App\Notifications\AuctionWinnerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $auction = new Auction($validated);
        $auction->user_id = Auth::id();
        $auction->current_price = $request->starting_price;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $filename  = time() . '_' . $imageFile->getClientOriginalName();
            $path = $imageFile->storeAs('public/images/auctions', $filename);
            $auction->image = $filename;
        }

        $auction->save();

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction created successfully!');
    }

    public function show(Auction $auction)
    {
        $isFollowed = FollowAuction::where('follow_auctions.user_id', Auth::id())
            ->where('follow_auctions.auction_id', $auction->auction_id)
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
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to edit this auction.');
        }

        // Validação
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category_id'   => 'required|exists:Category,category_id',
            'starting_price'=> 'required|numeric|min:0',
            'reserve_price' => 'required|numeric|min:0',
            'starting_date' => 'required|date',
            'ending_date'   => 'required|date|after:starting_date',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string',
            'status'        => 'required|in:Active,Sold,Unsold,Upcoming,Closed',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 1) Preenche o objeto Auction com os campos validados (exceto imagem)
        $auction->fill($validated);

        // 2) Se há uma nova imagem, processa
        if ($request->hasFile('image')) {
            // Se havia uma imagem antiga, deleta (opcional) se não for 'default.png'
            if ($auction->image && $auction->image !== 'default.png') {
                \Storage::disk('public')->delete('images/auctions/'.$auction->image);
                // storage::disk significa que estamos a aceder o disco publico
            }


            $imageFile = $request->file('image');
            $filename  = time().'_'.preg_replace('/\s+/', '_', $imageFile->getClientOriginalName());

            $path = $imageFile->storeAs(
                'images/auctions', // pasta dentro de storage/app/public
                $filename,
                'public'
            );

            $auction->image = $filename;
            \Log::info('Image updated', [
                'auction_id' => $auction->auction_id,
                'image' => $filename,
            ]);
        }

        // 3) Agora salva *tudo* de uma vez
        $auction->save();

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction updated successfully!');
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
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to delete this auction.');
        }

        if (! in_array($auction->status, ['Active', 'Upcoming'])) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'This auction cannot be deleted.');
        }


        $auction->delete();

        return redirect()->route('profile.myauctions')
            ->with('success', 'Auction deleted successfully!');
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

        $bids = Bid::where('user_id', $user->user_id)
            ->orderBy('time', 'desc')
            ->get();

        return view('profile.bidding_history', compact('user', 'auctions', 'bids'));
    }


    public function followed()
    {
        $user = Auth::user();
        $followedAuctions = $user->followingAuctions;

        return view('auctions.followed', compact('followedAuctions'));
    }


    private function pickWinner(Auction $auction)
    {
        // Se o leilão não estiver 'Active', não faz nada
        if ($auction->status !== 'Active') {
            return;
        }

        // Pega o maior lance
        $highestBid = $auction->bids()
            ->orderBy('price', 'desc')
            ->first();

        if (!$highestBid) {
            // Sem lances => Unsold
            $auction->status    = 'Unsold';
            $auction->winner_id = null;
            $auction->save();
            return;
        }
/*
        // Se o lance for menor que reserve_price => Unsold
        if ($highestBid->price < $auction->reserve_price) {
            $auction->status    = 'Unsold';
            $auction->winner_id = null;
            $auction->save();
            return;
        }
*/
        // Vendeu
        $auction->status        = 'Sold';
        $auction->winner_id     = $highestBid->user_id;
        $auction->current_price = $highestBid->price;
        $auction->save();

        // Cria Transaction
        $transaction = new Transaction();
        $transaction->buyer_id         = $highestBid->user_id;
        $transaction->auction_id       = $auction->auction_id;
        $transaction->payment_method_id = null;  // ajuste conforme necessário
        $transaction->value            = $highestBid->price;
        $transaction->created_at       = now();
        $transaction->payment_deadline = now()->addDays(7);
        $transaction->status           = 'Done';
        $transaction->save();

        // Cria Order
        $order = new Order();
        $order->transaction_id = $transaction->transaction_id;
        $order->created_at     = now();
        $order->save();

        // Notificar todos que participaram do leilão + o dono
        $owner   = $auction->user;
        $bidders = $auction->bids()->pluck('user_id')->unique();

        $notifiables = User::whereIn('user_id', $bidders)
            ->orWhere('user_id', $owner->user_id)
            ->get();

        // AuctionEndedNotification
        foreach ($notifiables as $notifiable) {
            $notifiable->notify(new AuctionEndedNotification($auction));
        }

        // Se tem winner => AuctionWinnerNotification
        if ($auction->winner_id) {
            $winner = User::find($auction->winner_id);
            if ($winner) {
                foreach ($notifiables as $notifiable) {
                    $notifiable->notify(new AuctionWinnerNotification($auction, $winner));
                }
            }
        }
    }

    /**
     * Encerrar o leilão definindo explicitamente um winner_id no formulário.
     */
    public function endAuction(Request $request, Auction $auction)
    {
        if (Auth::id() !== $auction->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'winner_id' => 'required|exists:User,user_id',
        ]);

        // Se não estiver 'Active' ou 'Upcoming', não pode encerrar
        if (!in_array($auction->status, ['Active', 'Upcoming'])) {
            return redirect()->back()->with('error', 'Auction cannot be ended in its current status.');
        }

        // Verifica se a pessoa escolhida de fato deu lance
        $winnerBid = $auction->bids()
            ->where('user_id', $request->winner_id)
            ->orderBy('price', 'desc')
            ->first();

        if (!$winnerBid) {
            return redirect()->back()
                ->with('error', 'Selected winner not found among the bids.');
        }

        /*
        // Se não atinge reserve price => Unsold
        if ($winnerBid->price < $auction->reserve_price) {
            $auction->status    = 'Unsold';
            $auction->winner_id = null;
            $auction->save();
            // Chamamos pickWinner para gerar Notificação => Ended?
            $this->pickWinner($auction);

            return redirect()->route('auctions.show', $auction)
                ->with('success', 'Auction ended successfully!');
        }
*/
        // Vendeu
        $auction->status      = 'Sold';
        $auction->winner_id   = $winnerBid->user_id;
        $auction->current_price = $winnerBid->price;
        $auction->save();

        // Aqui, se quiser disparar pickWinner, poderia
        // MAS se já definimos status 'Sold', então chamamos pickWinner p/ transaction + notifs
        $this->pickWinner($auction);

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction ended successfully and winner has been selected.');
    }

    /**
     * Encerrar o leilão manualmente (sem winner_id), definindo automaticamente via pickWinner.
     */
    public function endManually(Auction $auction)
    {
        if ($auction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Se o leilão está Active ou não
        if (!in_array($auction->status, ['Active', 'Upcoming'])) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Auction cannot be ended in its current status.');
        }

        // Chamamos pickWinner, que decide se é Sold ou Unsold
        $this->pickWinner($auction);

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction ended!');
    }

    /**
     * Checa se algum leilão expirou (data passou) e chama pickWinner.
     * Chamado, por ex., via schedule (cron) no app/Console/Kernel.php
     */
    public function checkAndNotifyEndingAuctions($minutesLeft = 5)
    {
        // Pega agora + $minutesLeft
        $threshold = now()->addMinutes($minutesLeft);

        // Localiza auctions que estão 'Active',
        // cujo ending_date está entre agora e threshold
        $aboutToEndAuctions = Auction::where('status', 'Active')
            ->where('ending_date', '>', now())        // ainda não acabou
            ->where('ending_date', '<=', $threshold)  // acaba em <= X minutos
            ->get();

        foreach ($aboutToEndAuctions as $auction) {
            // Monta lista de usuários que receberão a notificação
            $ownerId = $auction->user_id;
            $participantIds = $auction->bids()->pluck('user_id')
                ->merge($auction->followers->pluck('user_id'))
                ->unique();

            $userIds = $participantIds->merge([$ownerId])->unique();
            $users   = User::whereIn('user_id', $userIds)->get();

            // Envia a notificação
            \Illuminate\Support\Facades\Notification::send(
                $users,
                new AuctionEndingNotification($auction, $minutesLeft)
            );
        }

        return count($aboutToEndAuctions);
    }

    /**
     * Retorna leilões do user logado que foram 'Sold'.
     */
    public function soldAuctions()
    {
        $userId = Auth::id();
        $soldAuctions = Auction::where('user_id', $userId)
            ->where('status', 'Sold')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('profile.soldauctions', compact('soldAuctions'));
    }

    /**
     * Notifica manualmente que o leilão está prestes a acabar (auction_ending).
     */
    public function notifyAuctionEnding(Auction $auction)
    {
        $ownerId = $auction->user_id;

        // Participantes (quem deu lance ou segue)
        $participantIds = $auction->bids()->pluck('user_id')
            ->merge($auction->followers()->pluck('user_id'))
            ->unique();

        $userIds = $participantIds->merge([$ownerId])->unique();
        $users   = User::whereIn('user_id', $userIds)->get();

        // Dispara AuctionEndingNotification
        NotificationFacade::send($users, new AuctionEndingNotification($auction, 30));
    }

    /**
     * Cancela o leilão (status = Closed), reembolsa o último lance e notifica todos (auction_canceled).
     */
    public function cancelAuction(Auction $auction)
    {
        if ($auction->user_id !== Auth::id()  || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Permite cancelar apenas se estiver 'Active' ou 'Upcoming'
        if (!in_array($auction->status, ['Active', 'Upcoming'])) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Auction cannot be canceled in its current status.');
        }

        // Reembolsa o maior lance
        $highestBid = $auction->bids()->orderBy('price', 'desc')->first();
        if ($highestBid) {
            $lastBidder = User::find($highestBid->user_id);
            if ($lastBidder) {
                $lastBidder->credits += $highestBid->price;
                $lastBidder->save();

                Log::info('Last bidder refunded after cancelation', [
                    'auction_id' => $auction->auction_id,
                    'bidder_id'  => $lastBidder->user_id,
                    'amount'     => $highestBid->price,
                ]);
            }
        }

        // Marca como Closed
        $auction->status = 'Closed';
        $auction->save();

        // Dispara AuctionCanceledNotification para dono + participantes/seguidores
        $notifiablesIds = $auction->bids()
            ->pluck('user_id')
            ->merge($auction->followers()->pluck('user_id'))
            ->merge([$auction->user_id])
            ->unique();

        $notifiables = User::whereIn('user_id', $notifiablesIds)->get();
        foreach ($notifiables as $user) {
            $user->notify(new AuctionCanceledNotification($auction, 'Manually canceled by owner.'));
        }

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction has been canceled successfully (status: Closed).');
    }
}
