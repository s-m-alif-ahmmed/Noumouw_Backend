<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;


class NotificationController extends Controller
{
    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->delete();
            return response()->json([
                'success' => true,
                'message' => 'Notification has been deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ]);
        }
    }

    public function markAllRead()
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json([
                'success' => true,
                'message' => 'Notifications marked as read'
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
