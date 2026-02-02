<?php
// app/Http/Controllers/Admin/CommentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Danh sách bình luận
    public function index(Request $request)
    {
        $query = BinhLuan::with(['nguoiDung', 'sanPham'])
                         ->orderBy('NgayBinhLuan', 'desc');
        
        // Lọc theo trạng thái
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('TrangThai', $request->trang_thai);
        }
        
        // Lọc theo đã xem/chưa xem
        if ($request->has('da_xem') && $request->da_xem != '') {
            $query->where('DaXem', $request->da_xem == '1');
        }
        
        // Tìm kiếm theo nội dung
        if ($request->has('search') && $request->search != '') {
            $query->where('NoiDung', 'like', '%' . $request->search . '%');
        }
        
        $binhLuans = $query->paginate(20);
        
        // Đếm số lượng bình luận chưa xem
        $chuaXem = BinhLuan::where('DaXem', false)->count();
        
        return view('admin.comments.index', compact('binhLuans', 'chuaXem'));
    }
    
    // Đánh dấu đã xem
    public function markAsRead($id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->update(['DaXem' => true]);
        
        return back()->with('success', 'Đã đánh dấu đã xem!');
    }
    
    // Đánh dấu tất cả đã xem
    public function markAllAsRead()
    {
        BinhLuan::where('DaXem', false)->update(['DaXem' => true]);
        
        return back()->with('success', 'Đã đánh dấu tất cả đã xem!');
    }
    
    // Cập nhật trạng thái (duyệt/ẩn)
    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'TrangThai' => 'required|in:cho_duyet,da_duyet,bi_an'
        ]);
        
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->update([
            'TrangThai' => $request->TrangThai,
            'DaXem' => true, // Tự động đánh dấu đã xem khi admin thao tác
        ]);
        
        return back()->with('success', 'Đã cập nhật trạng thái!');
    }
    
    // Đánh dấu đã xem khi click vào link sản phẩm
    public function viewProduct($id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->update(['DaXem' => true]);
        
        return redirect()->route('products.show', $binhLuan->MaSanPham);
    }
    
    // Xóa bình luận
    public function destroy($id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->delete();
        
        return back()->with('success', 'Đã xóa bình luận!');
    }
}