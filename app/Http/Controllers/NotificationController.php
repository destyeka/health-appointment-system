<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notificationsQuery = Notification::query()
            ->with('user'); 

        if (!$user->isAdmin()) {
            $notificationsQuery->where('id_user', $user->id_user);
        } 
        
        $notifications = $notificationsQuery
            ->orderBy('sent_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function destroy(Notification $notification)
    {
        $user = Auth::user();
        
        if ($notification->id_user !== $user->id_user && !$user->hasPermission('delete_notification')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus notifikasi ini.');
        }

        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}