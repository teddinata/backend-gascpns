<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function sendNotification($userId, $title, $message, $link = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'transaction',
            'link' => $link,
        ]);
    }

    public static function getUserNotifications($userId)
    {
        return Notification::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
        }
    }
}
