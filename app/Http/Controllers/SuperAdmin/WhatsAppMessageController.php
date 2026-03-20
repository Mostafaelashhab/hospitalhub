<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;

class WhatsAppMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = WhatsappMessage::latest('message_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('from_number', 'like', "%{$search}%")
                  ->orWhere('sender_name', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($request->input('unread')) {
            $query->where('is_read', false);
        }

        $messages = $query->paginate(30);
        $unreadCount = WhatsappMessage::where('is_read', false)->count();

        return view('super-admin.whatsapp.index', compact('messages', 'unreadCount'));
    }

    public function markAsRead(WhatsappMessage $message)
    {
        $message->update(['is_read' => true]);
        return back();
    }

    public function markAllAsRead()
    {
        WhatsappMessage::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم تعليم الكل كمقروء' : 'All marked as read');
    }
}
