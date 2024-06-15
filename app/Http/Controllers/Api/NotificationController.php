<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function sendAdminNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string',
            // 'type' => 'required|string|in:transaction,information,warning',
        ]);

        $users = User::all();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => 'information',
                'link' => $request->link,
            ]);
        }

        return response()->json(['message' => 'Notification sent to all users']);
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = NotificationService::sendNotification(
            $request->user_id,
            $request->title,
            $request->message
        );

        return response()->json(['notification' => $notification], 201);
    }

    public function index()
    {
        $userId = Auth::id();
        $notifications = NotificationService::getUserNotifications($userId);
        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead($id)
    {
        NotificationService::markAsRead($id);
        return response()->json(['message' => 'Notification marked as read']);
    }
}
