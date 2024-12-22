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


        try {
            // Verificar se o `auction_id` é válido antes de inserir
            if (!$auction) {
                return redirect()
                    ->back()
                    ->withErrors(['error' => 'Leilão não encontrado.']);
            }

            // Notificar o seller diretamente
            DB::table('notification')->insert([
                'user_id'    => $auction->user_id,
                'auction_id' => $auction->auction_id,
                'content'    => __('Your auction :title was canceled by an admin. Reason: :reason', [
                    'title' => $auction->title,
                    'reason' => $request->reason,
                ]),
                'type'       => 'cancellation',
                'created_at' => now(),
            ]);

            // Apagar o leilão
            $auction->delete();

            return redirect()
                ->back()
                ->with('success', 'Leilão cancelado e notificação enviada ao criador.');
        } catch (\Exception $e) {
            // Capturar qualquer erro durante a inserção
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erro ao cancelar o leilão: ' . $e->getMessage()]);
        }
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
        $auction->save();

        // Notificar o seller diretamente
        DB::table('notification')->insert([
            'user_id'    => $auction->user_id,
            'auction_id' => $auction->auction_id,
            'content'    => __('Your auction :title was suspended by an admin. Reason: :reason', [
                'title' => $auction->title,
                'reason' => $request->reason
            ]),
            'type'       => 'suspension',
            'created_at' => now(),
            'bid_id' => 0
        ]);

        return redirect()
            ->back()
            ->with('success', 'Leilão suspenso e notificação enviada ao criador.');
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
        $auction->save();

        // Notificar o seller diretamente
        DB::table('notification')->insert([
            'user_id'    => $auction->user_id,
            'auction_id' => $auction->auction_id,
            'content'    => __('Your auction :title was reactivated by an admin.', [
                'title' => $auction->title,
            ]),
            'type'       => 'reactivation',
            'created_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Leilão reativado e notificação enviada ao criador.');
    }
}
