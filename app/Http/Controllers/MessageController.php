<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auction;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function index()
    {
        $auctions = $this->getUserAuctions();
        return view('messages.index', compact('auctions'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|integer|exists:Auction,auction_id',
            'text' => 'required|string'
        ]);

        $user = Auth::user();
        $auction_id = $request->auction_id;
        $auction = Auction::find($auction_id);
        $otherUserId = $auction->user_id;

        $chat = $this->findOrCreateChat($user->user_id, $otherUserId, $auction_id);

        $message = Message::create([
            'chat_id' => $chat->chat_id,
            'sender_id' => $user->user_id,
            'text' => $request->text,
            'time' => Carbon::now(),
            'auction_id' => $auction_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => [
                'message_id' => $message->message_id,
                'sender_name' => $user->fullname,
                'text' => e($message->text),
                'time' => Carbon::parse($message->time)->format('Y-m-d H:i:s'),
                'is_me' => true
            ]
        ]);
    }

    public function startChat(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|integer|exists:Auction,auction_id'
        ]);

        $user = Auth::user();
        $auction_id = $request->auction_id;
        $auction = Auction::find($auction_id);
        $this->findOrCreateChat($user->user_id, $auction->user_id, $auction_id);

        return redirect()->route('messages.index', ['auction_id' => $auction_id]);
    }

    public function loadChat(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|integer|exists:Auction,auction_id'
        ]);

        $user = Auth::user();
        $auction_id = $request->auction_id;
        $auction = Auction::find($auction_id);

        if (!$auction || !$auction->user_id) {
            return response()->json(['messages' => []]);
        }

        $otherUserId = $auction->user_id;
        $chat = $this->findOrCreateChat($user->user_id, $otherUserId, $auction_id);

        $messages = $chat->messages()->with('sender')->orderBy('message_id', 'asc')->get();

        $formatted = $messages->map(function($m) use ($user) {
            $senderName = 'Desconhecido';
            if ($m->sender) {
                $senderName = $m->sender->fullname ?? $m->sender->username ?? $m->sender->email ?? 'SemNome';
            }
            return [
                'message_id' => $m->message_id,
                'sender_name' => $senderName,
                'text' => e($m->text),
                'time' => Carbon::parse($m->time)->format('Y-m-d H:i:s'),
                'is_me' => $user->user_id === $m->sender_id
            ];
        });

        return response()->json(['messages' => $formatted]);
    }

    public function pollMessages(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|integer|exists:Auction,auction_id',
            'last_message_id' => 'required|integer'
        ]);

        $user = Auth::user();
        $auction_id = $request->auction_id;
        $auction = Auction::find($auction_id);

        if (!$auction || !$auction->user_id) {
            return response()->json(['messages' => []]);
        }

        $otherUserId = $auction->user_id;
        $chat = $this->findOrCreateChat($user->user_id, $otherUserId, $auction_id);

        $newMessages = $chat->messages()
            ->with('sender')
            ->where('message_id', '>', $request->last_message_id)
            ->orderBy('message_id', 'asc')
            ->get();

        $formatted = $newMessages->map(function($m) use ($user) {
            $senderName = 'Desconhecido';
            if ($m->sender) {
                $senderName = $m->sender->fullname ?? $m->sender->username ?? $m->sender->email ?? 'SemNome';
            }
            return [
                'message_id' => $m->message_id,
                'sender_name' => $senderName,
                'text' => e($m->text),
                'time' => Carbon::parse($m->time)->format('Y-m-d H:i:s'),
                'is_me' => $user->user_id === $m->sender_id
            ];
        });

        return response()->json(['messages' => $formatted]);
    }

    /**
     * Polling de novos chats.
     * Retorna a lista atualizada de auctions do usuário, mesma lógica do index,
     * mas em JSON, para podermos atualizar a barra lateral.
     */
    public function pollChats(Request $request)
    {
        // Podemos simplesmente retornar todas as auctions, caso novas apareçam, serão adicionadas.
        $auctions = $this->getUserAuctions();

        // Retorna em um formato simples: uma lista de auctions com auction_id e title
        $formatted = $auctions->map(function($a) {
            return [
                'auction_id' => $a->auction_id,
                'title' => $a->title
            ];
        });

        return response()->json(['auctions' => $formatted]);
    }

    private function findOrCreateChat($userId, $otherUserId, $auction_id)
    {
        $chatIdsUser = ChatParticipant::where('user_id', $userId)->pluck('chat_id');

        // Encontrar um chat que tenha o outro user e o mesmo auction_id
        $chatWithOther = ChatParticipant::whereIn('chat_id', $chatIdsUser)
            ->where('user_id', $otherUserId)
            ->get()
            ->map(function($cp) {
                return $cp->chat;
            })
            ->first(function($chat) use ($auction_id) {
                return $chat->auction_id == $auction_id;
            });

        if ($chatWithOther) {
            return $chatWithOther;
        } else {
            $newChat = Chat::create([
                'auction_id' => $auction_id,
                'is_private' => true,
                'created_at' => Carbon::now()
            ]);

            ChatParticipant::create([
                'chat_id' => $newChat->chat_id,
                'user_id' => $userId,
                'joined_at' => Carbon::now()
            ]);

            ChatParticipant::create([
                'chat_id' => $newChat->chat_id,
                'user_id' => $otherUserId,
                'joined_at' => Carbon::now()
            ]);

            return $newChat;
        }
    }

    private function getUserAuctions()
    {
        $user = Auth::user();
        $chatIds = ChatParticipant::where('user_id', $user->user_id)->pluck('chat_id');
        $auctionIds = Message::whereIn('chat_id', $chatIds)
            ->distinct()
            ->pluck('auction_id');

        return Auction::whereIn('auction_id', $auctionIds)->orderBy('title', 'asc')->get();
    }
}
