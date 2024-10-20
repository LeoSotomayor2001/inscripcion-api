<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Obtener todas las notificaciones del usuario autenticado
        $notifications = Auth::user()->notifications;

        return response()->json($notifications);
    }

    public function unReadNotifications(){
        $notifications = Auth::user()->unreadNotifications;
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        // Obtener la notificación específica del usuario autenticado
        $notification = Auth::user()->unreadNotifications->find($id);
    
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['mensaje' => 'Notificación marcada como leída']);
        }
    
        return response()->json(['mensaje' => 'Notificación no encontrada'], 404);
    }
    

    public function markAllAsRead()
    {
        // Marcar todas las notificaciones como leídas
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['mensaje' => 'Todas las notificaciones marcadas como leídas']);
    }
}
