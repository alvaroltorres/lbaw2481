<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Lista todas as orders do usuário autenticado (ou seja, a buyer_id = Auth::id()).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Carregamos as orders relacionadas a transactions em que buyer_id = userId
        $orders = Order::with(['transaction.auction'])
            ->whereHas('transaction', function ($query) use ($userId) {
                $query->where('buyer_id', $userId);
            })
            ->orderBy('order_id', 'desc') // ou outro critério
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
