<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and update the read_at column.
     *
     * @param int $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($notificationId)
    {
        $user = auth()->user();  // Get the currently authenticated user
        $notification = $user->notifications->find($notificationId);  // Find the notification by ID

        if ($notification) {
            // Set the read_at column to the current timestamp
            $notification->read_at = now();
            $notification->save();  // Save the notification

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Notification not found'], 404);
        }
    }

    // Add other methods for listing notifications, etc.
}
