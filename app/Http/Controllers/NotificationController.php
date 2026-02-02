<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // API: Lấy số lượng thông báo chưa đọc (cho polling)
    public function getUnreadCount()
    {
        $count = ThongBao::where('MaNguoiDung', Auth::id())
            ->chuaDoc()
            ->count();
        
        return response()->json(['count' => $count]);
    }

    // API: Lấy danh sách thông báo (cho dropdown)
    public function getNotifications()
    {
        $notifications = ThongBao::where('MaNguoiDung', Auth::id())
            ->orderBy('ThoiGian', 'desc')
            ->limit(10) // Chỉ lấy 10 thông báo mới nhất
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->MaThongBao,
                    'title' => $notif->TieuDe,
                    'content' => $notif->NoiDung,
                    'link' => $notif->Link,
                    'icon' => $notif->icon(),
                    'time' => $notif->thoiGianHienThi(),
                    'read' => $notif->DaDoc,
                ];
            });
        
        return response()->json(['notifications' => $notifications]);
    }

    // Trang danh sách tất cả thông báo
    public function index()
    {
        $thongBaos = ThongBao::where('MaNguoiDung', Auth::id())
            ->orderBy('ThoiGian', 'desc')
            ->paginate(20);
        
        return view('notifications.index', compact('thongBaos'));
    }

    // Đánh dấu 1 thông báo đã đọc
    public function markAsRead($id)
    {
        $notification = ThongBao::where('MaNguoiDung', Auth::id())
            ->findOrFail($id);
        
        $notification->update(['DaDoc' => true]);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        // Redirect đến link thông báo
        if ($notification->Link) {
            return redirect($notification->Link);
        }
        
        return back();
    }

    // Đánh dấu TẤT CẢ thông báo đã đọc
    public function markAllAsRead()
    {
        ThongBao::where('MaNguoiDung', Auth::id())
            ->chuaDoc()
            ->update(['DaDoc' => true]);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã đánh dấu tất cả đã đọc']);
        }
        
        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }

    // Xóa thông báo
    public function destroy($id)
    {
        $notification = ThongBao::where('MaNguoiDung', Auth::id())
            ->findOrFail($id);
        
        $notification->delete();
        
        return back()->with('success', 'Đã xóa thông báo!');
    }

    // Xóa tất cả thông báo đã đọc
    public function clearRead()
    {
        ThongBao::where('MaNguoiDung', Auth::id())
            ->daDoc()
            ->delete();
        
        return back()->with('success', 'Đã xóa tất cả thông báo đã đọc!');
    }
}