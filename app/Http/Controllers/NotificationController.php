<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Lista de notificações (com paginação).
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Exibe formulário de criação (se for necessário).
     */
    public function create()
    {
        //
    }

    /**
     * Salva algo no banco (se for necessário).
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Mostra uma notificação específica.
     */
    public function show($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        // Marcar como lida, se ainda não estiver
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }

    /**
     * Editar notificação (se for necessário).
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Atualizar notificação (se for necessário).
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Deletar notificação (se for necessário).
     */
    public function destroy(Notification $notification)
    {
        //
    }

    /**
     * Retorna as novas notificações (não lidas) em JSON.
     */
    public function fetchNewNotifications()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([], 401);
        }

        // Todas as notificações não lidas
        $notifications = $user->unreadNotifications;

        // Mapeia para JSON
        $notificationsData = $notifications->map(function ($notification) {
            $data = $notification->data;

            // Se a notificação tiver 'bid_amount', formatamos
            if (isset($data['bid_amount'])) {
                $data['bid_amount_formatted'] = number_format($data['bid_amount'], 2, ',', '.');
            } else {
                $data['bid_amount_formatted'] = null;
            }

            return [
                'id'         => $notification->id,
                'type'       => class_basename($notification->type),
                'data'       => $data,
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        // Agora retornamos em JSON
        return response()->json($notificationsData);
    }

    /**
     * Marcar notificação como lida (via POST).
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([], 401);
        }

        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'not found'], 404);
    }

    /**
     * Retorna o número de notificações não lidas.
     */
    public function unreadCount()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        $count = $user->unreadNotifications->count();

        return response()->json(['count' => $count]);
    }
}
