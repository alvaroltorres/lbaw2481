<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Transaction;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class RatingController extends Controller
{
    /**
     * Exibe o formulário para avaliar (rating) o vendedor de uma Transaction específica.
     */
    public function create($transaction_id)
    {
        $transaction = Transaction::with('auction')->findOrFail($transaction_id);

        // Verifica se o user logado é o comprador
        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'You are not authorized to rate this transaction.');
        }

        // Verifica se já existe rating para esta transaction (para evitar duplicados)
        $existingRating = Rating::where('transaction_id', $transaction_id)->first();
        if ($existingRating) {
            return redirect()->route('profile.orders')
                ->with('error', 'You have already rated this transaction.');
        }

        $auction = $transaction->auction; // Para exibir detalhes no form

        return view('ratings.create', compact('transaction', 'auction'));
    }

    /**
     * Salva a avaliação no banco.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transaction,transaction_id',
            'score'          => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:1000',
        ]);

        $transaction = Transaction::with('auction')->findOrFail($request->transaction_id);

        // Só o comprador (buyer_id) pode avaliar o vendedor (auction->user_id)
        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'You are not authorized to rate this transaction.');
        }

        // Verifica se já existe rating para esta transaction
        $existingRating = Rating::where('transaction_id', $transaction->transaction_id)->first();
        if ($existingRating) {
            return redirect()->route('profile.orders')
                ->with('error', 'You have already rated this transaction.');
        }

        // Cria o rating
        $rating = new Rating();
        $rating->rated_user_id  = $transaction->auction->user_id; // seller
        $rating->rater_user_id  = Auth::id();                     // buyer
        $rating->transaction_id = $transaction->transaction_id;
        $rating->score          = $request->score;
        $rating->comment        = $request->comment;
        $rating->rating_time    = now(); // se quiser registrar a data/hora

        $rating->save();

        return redirect()->route('profile.orders')
            ->with('success', 'Your rating has been submitted successfully!');
    }

    /**
     * Lista as avaliações recebidas por um determinado usuário (rated_user_id).
     */
    public function index($user_id)
    {
        // Podemos checar se o user_id existe etc. ou assumimos que sim
        $ratings = Rating::with(['transaction.auction','raterUser'])
            ->where('rated_user_id', $user_id)
            ->orderBy('rating_time', 'desc')
            ->get();

        return view('ratings.index', compact('ratings'));
    }
}
