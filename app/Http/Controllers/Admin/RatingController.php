<?php
// app/Http/Controllers/Admin/RatingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\ThongBao;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // Danh sách đánh giá
    public function index(Request $request)
    {
        $query = DanhGia::with(['nguoiDung', 'sanPham', 'donHang'])
                        ->orderBy('NgayDanhGia', 'desc');
        
        // Lọc theo trạng thái
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('TrangThai', $request->trang_thai);
        }
        
        // Lọc theo số sao
        if ($request->has('so_sao') && $request->so_sao != '') {
            $query->where('SoSao', $request->so_sao);
        }
        
        // Lọc theo đã xem/chưa xem
        if ($request->has('da_xem') && $request->da_xem != '') {
            $query->where('DaXem', $request->da_xem == '1');
        }
        
        // Tìm kiếm theo nội dung
        if ($request->has('search') && $request->search != '') {
            $query->where('NoiDung', 'like', '%' . $request->search . '%');
        }
        
        $danhGias = $query->paginate(20);
        
        // Đếm số lượng đánh giá chưa xem
        $chuaXem = DanhGia::where('DaXem', false)->count();
        
        return view('admin.ratings.index', compact('danhGias', 'chuaXem'));
    }
    
    // Xem sản phẩm và tự động đánh dấu đã xem
    public function viewProduct($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->update(['DaXem' => true]);
        
        return redirect()->route('products.show', $danhGia->MaSanPham);
    }
    
    // Đánh dấu đã xem (AJAX - Gọi khi mở modal "Xem chi tiết và trả lời")
    public function markAsRead($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->update(['DaXem' => true]);
        
        // Trả về JSON cho AJAX request
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Đã đánh dấu đã xem!');
    }
    
    // Đánh dấu tất cả đã xem
    public function markAllAsRead()
    {
        DanhGia::where('DaXem', false)->update(['DaXem' => true]);
        
        return back()->with('success', 'Đã đánh dấu tất cả đã xem!');
    }
    
    // Cập nhật trạng thái (duyệt/ẩn)
    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'TrangThai' => 'required|in:cho_duyet,da_duyet,bi_an'
        ]);
        
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->update(['TrangThai' => $request->TrangThai]);
        
        return back()->with('success', 'Đã cập nhật trạng thái!');
    }
    
    // Xóa đánh giá
    public function destroy($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        
        // Xóa ảnh nếu có
        if ($danhGia->HinhAnh) {
            foreach ($danhGia->HinhAnh as $path) {
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }
        }
        
        $danhGia->delete();
        
        return back()->with('success', 'Đã xóa đánh giá!');
    }

    // Phản hồi đánh giá từ shop (TỰ ĐỘNG ĐÁNH DẤU ĐÃ XEM)
    public function reply(Request $request, $id)
    {
        $request->validate([
            'PhanHoiShop' => 'required|min:10|max:1000',
        ], [
            'PhanHoiShop.required' => 'Vui lòng nhập nội dung phản hồi',
            'PhanHoiShop.min' => 'Phản hồi tối thiểu 10 ký tự',
            'PhanHoiShop.max' => 'Phản hồi tối đa 1000 ký tự',
        ]);

        $danhGia = DanhGia::findOrFail($id);
        $danhGia->update([
            'PhanHoiShop' => $request->PhanHoiShop,
            'NguoiPhanHoi' => auth()->id(),
            'NgayPhanHoi' => now(),
            'DaXem' => true, // Tự động đánh dấu đã xem khi phản hồi
        ]);
        // TẠO THÔNG BÁO KHI SHOP TRẢ LỜI ĐÁNH GIÁ
        $tenSanPham = $danhGia->sanPham->TenSanPham ?? 'sản phẩm';
        
        ThongBao::taoThongBao(
            $danhGia->MaNguoiDung, // Người nhận
            'review_reply',
            'Shop đã trả lời đánh giá của bạn',
            "Shop đã phản hồi đánh giá $danhGia->SoSao sao của bạn về $tenSanPham",
            route('products.show', $danhGia->MaSanPham) . '#review-' . $danhGia->MaDanhGia
        );

        return back()->with('success', 'Đã gửi phản hồi!');
    }

    // Xóa phản hồi
    public function deleteReply($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->update([
            'PhanHoiShop' => null,
            'NguoiPhanHoi' => null,
            'NgayPhanHoi' => null,
        ]);

        return back()->with('success', 'Đã xóa phản hồi!');
    }
}