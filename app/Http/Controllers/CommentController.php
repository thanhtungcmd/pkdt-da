<?php
// app/Http/Controllers/CommentController.php
// THAY THẾ phần store() hiện tại bằng code này

namespace App\Http\Controllers;

use App\Models\BinhLuan;
use App\Models\ThongBao; // THÊM
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Thêm bình luận
    public function store(Request $request)
    {
        Log::info('Comment Request:', $request->all());
        
        $request->validate([
            'MaSanPham' => 'required|exists:san_pham,MaSanPham',
            'NoiDung' => 'required|min:10|max:1000',
            'MaBinhLuanCha' => 'nullable|exists:binh_luan,MaBinhLuan',
        ], [
            'NoiDung.required' => 'Vui lòng nhập nội dung bình luận',
            'NoiDung.min' => 'Bình luận tối thiểu 10 ký tự',
            'NoiDung.max' => 'Bình luận tối đa 1000 ký tự',
        ]);

        try {
            $isAdmin = Auth::user()->VaiTro == 1;

            $comment = BinhLuan::create([
                'MaNguoiDung' => Auth::id(),
                'MaSanPham' => $request->MaSanPham,
                'NoiDung' => $request->NoiDung,
                'NgayBinhLuan' => Carbon::now(),
                'MaBinhLuanCha' => $request->MaBinhLuanCha,
                'TrangThai' => 'da_duyet',
                'DaXem' => $isAdmin,
            ]);
            
            Log::info('Comment Created:', $comment->toArray());

            
            // TẠO THÔNG BÁO KHI TRẢ LỜI BÌNH LUẬN
            if ($request->MaBinhLuanCha) {
                $parentComment = BinhLuan::with('nguoiDung', 'sanPham')->find($request->MaBinhLuanCha);
                
                if ($parentComment && $parentComment->MaNguoiDung != Auth::id()) {
                    // Chỉ tạo thông báo nếu không phải tự trả lời chính mình
                    
                    $tenNguoiTraLoi = Auth::user()->HoTen;
                    $tenSanPham = $parentComment->sanPham->TenSanPham ?? 'sản phẩm';
                    
                    ThongBao::taoThongBao(
                        $parentComment->MaNguoiDung, // Người nhận thông báo
                        'comment_reply',
                        'Có người trả lời bình luận của bạn',
                        "$tenNguoiTraLoi đã trả lời bình luận của bạn về $tenSanPham",
                        route('products.show', $request->MaSanPham) . '#comment-' . $comment->MaBinhLuan
                    );
                }
                
                // Đánh dấu comment cha là chưa xem
                $parentComment->update(['DaXem' => false]);
            }

            return back()->with('success', $request->MaBinhLuanCha ? 'Đã gửi trả lời!' : 'Đã gửi bình luận!');
            
        } catch (\Exception $e) {
            Log::error('Comment Error:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Xóa bình luận (giữ nguyên)
    public function destroy($id)
    {
        $binhLuan = BinhLuan::where('MaNguoiDung', Auth::id())
            ->findOrFail($id);

        $binhLuan->delete();

        return back()->with('success', 'Đã xóa bình luận!');
    }
}