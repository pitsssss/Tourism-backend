<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class AdminNotificationController extends Controller
{
    public function send(Request $request) {
        $users = User::all();
        Notification::send($users, new GeneralNotification($request->message));
        return response()->json(['message' => 'Notification sent']);
    }
}
