<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAuctionController extends Controller
{
    /**
     * Lista todos os leilões (ou filtra conforme quiseres).
     */
    public function index()
    {
        // Exemplo: Obter todos os leilões, mesmo cancelados/suspensos
        $auctions = Auction::with('seller')->get(); 
        return view('admin.auctions.index', compact('auctions'));
    }

    /**
     * Exibe detalhes de um leilão específico (admin).
     */
    public function show(Auction $auction)
    {
        return view('admin.auctions.show', compact('auction'));
    }

    /**
     * Cancela o leilão (status = "cancelled").
     */
    public function cancel(Request $request, Auction $auction)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $auction->status = 'cancelled';
        $auction->cancel_reason = $request->reason;
        $auction->save();

        // Notificar o seller
        DB::table('notification')->insert([
            'user_id'    => $auction->user_id, // seller
            'content'    => "O seu leilão \"{$auction->title}\" foi cancelado pelo admin. Razão: {$request->reason}",
            'created_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Leilão cancelado com sucesso.');
    }

    /**
     * Suspende o leilão (status = "suspended").
     */
    public function suspend(Request $request, Auction $auction)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $auction->status = 'suspended';
        $auction->cancel_reason = $request->reason; 
        $auction->save();

        // Notificar o seller
        DB::table('notification')->insert([
            'user_id'    => $auction->user_id,
            'content'    => "O seu leilão \"{$auction->title}\" foi suspenso pelo admin. Razão: {$request->reason}",
            'created_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Leilão suspenso com sucesso.');
    }

    /**
     * Reativa o leilão (remove a suspensão), status = "active".
     */
    public function unsuspend(Auction $auction)
    {
        if ($auction->status !== 'suspended') {
            return redirect()
                ->back()
                ->with('error', 'Este leilão não está suspenso.');
        }

        $auction->status = 'active';
        $auction->cancel_reason = null; // limpa a reason se quiseres
        $auction->save();

        // Notificar o seller
        DB::table('notification')->insert([
            'user_id'    => $auction->user_id,
            'content'    => "O seu leilão \"{$auction->title}\" foi reativado pelo admin.",
            'created_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Leilão reativado com sucesso.');
    }
}
