<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhanHoi;
use App\Models\TraLoiPhanHoi;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = PhanHoi::with('nguoiDung');

        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('TrangThai', $request->trang_thai);
        }

        $phanHoi = $query->latest('NgayGui')->paginate(15);

        return view('admin.feedback.index', compact('phanHoi'));
    }

    public function show($id)
    {
        $phanHoi = PhanHoi::with(['nguoiDung', 'traLoi.admin'])->findOrFail($id);

        if ($phanHoi->TrangThai == 0) {
            $phanHoi->update(['TrangThai' => 1]);
        }

        return view('admin.feedback.show', compact('phanHoi'));
    }

    // Thêm phương thức trả lời
    public function reply(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => 'required|string|min:10',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung trả lời',
            'noi_dung.min' => 'Nội dung trả lời phải có ít nhất 10 ký tự',
        ]);

        $phanHoi = PhanHoi::with('nguoiDung')->findOrFail($id);

        TraLoiPhanHoi::create([
            'MaPhanHoi' => $phanHoi->MaPhanHoi,
            'MaAdmin' => Auth::id(),
            'NoiDung' => $request->noi_dung,
            'NgayTraLoi' => now(),
        ]);
        // Đánh dấu đã xem
        $phanHoi->update(['TrangThai' => 1]);

        
        // TẠO THÔNG BÁO KHI SHOP TRẢ LỜI PHẢN HỒI (MỚI)
        ThongBao::taoThongBao(
            $phanHoi->MaNguoiDung, // Người nhận
            'feedback_reply',
            'Shop đã trả lời phản hồi của bạn',
            "Shop đã phản hồi về: \"{$phanHoi->TieuDe}\"",
            route('notifications.index') // Hoặc trang chi tiết feedback nếu có
        );

        return redirect()->route('admin.feedback.show', $id)
            ->with('success', 'Đã gửi trả lời thành công!');
    }

    // Xóa trả lời
    public function deleteReply($id)
    {
        $traLoi = TraLoiPhanHoi::findOrFail($id);
        $maPhanHoi = $traLoi->MaPhanHoi;
        $traLoi->delete();

        return redirect()->route('admin.feedback.show', $maPhanHoi)
            ->with('success', 'Đã xóa trả lời!');
    }

    public function destroy($id)
    {
        $phanHoi = PhanHoi::findOrFail($id);
        $phanHoi->delete();

        return redirect()->route('admin.feedback.index')
            ->with('success', 'Đã xóa phản hồi!');
    }
}