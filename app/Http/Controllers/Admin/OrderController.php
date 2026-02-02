<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\ThongBao;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $query = DonHang::with('nguoiDung');

        // Lọc theo trạng thái
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('TrangThai', $request->trang_thai);
        }

        $donHang = $query->latest('NgayDat')->paginate(15);
        
        // Lấy tất cả trạng thái để hiển thị filter
        $trangThai = DonHang::TRANG_THAI;

        return view('admin.orders.index', compact('donHang', 'trangThai'));
    }

    // Chi tiết đơn hàng
    public function show($id)
    {
        $donHang = DonHang::with(['nguoiDung', 'chiTiet.sanPham.sanPham'])
            ->findOrFail($id);

        // Lấy các trạng thái được phép chọn dựa trên trạng thái hiện tại
        $trangThaiAdmin = $this->getTrangThaiChoPhep($donHang->TrangThai);

        return view('admin.orders.show', compact('donHang', 'trangThaiAdmin'));
    }

    // Hàm xác định trạng thái được phép chọn theo quy trình
    private function getTrangThaiChoPhep($trangThaiHienTai)
    {
        $quyTrinh = [
            'Chờ xác nhận' => ['Chờ xác nhận', 'Đã xác nhận'],
            'Đã xác nhận' => ['Đã xác nhận', 'Đang giao hàng'],
            'Đang giao hàng' => ['Đang giao hàng', 'Đã giao hàng'],
            'Đã giao hàng' => ['Đã giao hàng'], // Không thể thay đổi
            'Đã hủy' => ['Đã hủy'], // Không thể thay đổi
        ];

        return $quyTrinh[$trangThaiHienTai] ?? [];
    }

    // Cập nhật trạng thái
    public function updateStatus(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);

        // Không cho phép thay đổi nếu đơn đã hủy hoặc đã giao hàng
        if (in_array($donHang->TrangThai, ['Đã hủy', 'Đã giao hàng'])) {
            return back()->with('error', 'Không thể thay đổi trạng thái đơn hàng "' . $donHang->TrangThai . '"!');
        }

        // Lấy các trạng thái được phép
        $trangThaiChoPhep = $this->getTrangThaiChoPhep($donHang->TrangThai);

        $request->validate([
            'TrangThai' => 'required|in:' . implode(',', $trangThaiChoPhep),
        ], [
            'TrangThai.in' => 'Trạng thái không hợp lệ! Bạn chỉ có thể chuyển sang trạng thái tiếp theo.',
        ]);

        $donHang->update([
            'TrangThai' => $request->TrangThai,
        ]);

        // TẠO THÔNG BÁO KHI ĐƠN HÀNG ĐÃ XÁC NHẬN
        if ($newStatus == 'Đã xác nhận' && $oldStatus == 'Chờ xác nhận') {
            ThongBao::taoThongBao(
                $donHang->MaNguoiDung, // Người nhận
                'order_confirmed',
                'Đơn hàng đã được xác nhận',
                "Đơn hàng #{$donHang->MaDonHang} của bạn đã được xác nhận và đang được chuẩn bị",
                route('orders.detail', $donHang->MaDonHang)
            );
        }
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $donHang = DonHang::findOrFail($id);

        if ($donHang->TrangThai != 'Đã hủy') {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy!');
        }

        $donHang->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng!');
    }
}