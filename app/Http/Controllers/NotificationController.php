<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications()
    {
        $user = auth()->user();
        $unreadNotifications = $user->unreadNotifications;
        $readNotifications = $user->notifications()->whereNotNull('read_at')->get();
        return view('admin.notification', compact('unreadNotifications', 'readNotifications'));
    }

    public function markAsRead($id)
    {
        $user = auth()->user();
        $user->notifications()->where('id', $id)->first()->markAsRead();
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
