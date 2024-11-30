<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $notification = auth()->user()->notifications()
            ->findOrFail($id);

        // Marcar como lida
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }

    /**
     * Fetch new notifications.
     */

    /**
     * Fetch new notifications.
     */
    public function fetchNewNotifications()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([], 401);
        }

        $notifications = $user->unreadNotifications;

        // Map the notifications to include necessary data
        $notificationsData = $notifications->map(function ($notification) {
            $data = $notification->data;
            // Add formatted amount
            $data['bid_amount_formatted'] = number_format($data['bid_amount'], 2, ',', '.');
            return [
                'id' => $notification->id,
                'type' => class_basename($notification->type),
                'data' => $data,
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        return response()->json($notificationsData);
    }

    /**
     * Mark notification as read.
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