<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        return view('buyer.notifications', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);
        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return back()->with('success', 'All notifications marked as read');
    }

    public function destroy(Notification $notification){
    // Verify the notification belongs to the authenticated user
    if ($notification->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $notification->delete();

    return back()->with('success', 'Notification deleted successfully');
    }

public function clearAll(){
    auth()->user()->notifications()->delete();

    return back()->with('success', 'All notifications cleared');
    }
}