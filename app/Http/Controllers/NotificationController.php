<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = auth('sanctum')->user();
        $user->update(['expo_push_token' => $request->input('expoPushToken')]);
        return response()->json(['message' => 'Subscribed to notifications']);
    }

    public function sendTestNotification(Request $request)
    {
        $user = auth('sanctum')->user();
        $message = $request->input('message');

        // Create and save a notification
        $notification = new Notification(['message' => $message]);
        $user->notifications()->save($notification);

        // Push the notification to Expo using Expo's API
        $expo = new Expo();
        $notificationData = [
            'to' => $user->expo_push_token,
            'sound' => 'default',
            'title' => 'Test Notification',
            'body' => $message,
        ];
        $expo->notify($notificationData);

        return response()->json(['message' => 'Test notification sent']);
    }}
