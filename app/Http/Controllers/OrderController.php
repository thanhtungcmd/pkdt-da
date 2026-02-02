<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\CTGioHang;
use App\Models\DonHang;
use App\Models\CTDonHang;
use App\Models\CTSanPham;
use App\Models\MaGiamGia;
use App\Models\LichSuMaGiamGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị trang thanh toán
    public function checkout(Request $request)
    {
        $gioHang = collect();

        // ============================================
        // FIX: Nếu là Mua ngay
        // ============================================
        if ($request->has('MaCTSanPham') && $request->has('SoLuong')) {
            $ctSanPham = CTSanPham::with('sanPham')->findOrFail($request->MaCTSanPham);
            $soLuong = (int) $request->SoLuong;

            // Kiểm tra tồn kho
            if ($ctSanPham->SoLuongTon < $soLuong) {
                return redirect()->back()->with('error', 'Sản phẩm không đủ số lượng trong kho!');
            }

            // Tạo object giống cấu trúc CTGioHang để view xử lý đồng nhất
            $item = new \stdClass();
            $item->MaCTGioHang = 'buy_now_' . $ctSanPham->MaCTSanPham; // ID tạm để truyền vào form
            $item->MaCTSanPham = $ctSanPham->MaCTSanPham;
            $item->SoLuong = $soLuong;
            $item->DonGia = $ctSanPham->DonGia;
            $item->sanPham = $ctSanPham; // Đây là CTSanPham có quan hệ với SanPham
            
            $gioHang->push($item);
        } 
        // ============================================
        // Nếu là từ giỏ hàng
        // ============================================
        else {
            $itemIds = $request->input('items', []);
            if (empty($itemIds)) {
                return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm!');
            }

            $gioHang = CTGioHang::with('sanPham.sanPham')
                ->where('MaNguoiDung', Auth::id())
                ->whereIn('MaCTGioHang', $itemIds)
                ->get();
        }

        $nguoiDung = Auth::user();

        // Tính tổng tiền
        $tongTien = 0;
        foreach ($gioHang as $item) {
            $tongTien += $item->SoLuong * $item->sanPham->DonGia;
        }

        return view('orders.checkout', compact('gioHang', 'tongTien', 'nguoiDung'));
    }

    // Xử lý đặt hàng - CÓ TÍCH HỢP MÃ GIẢM GIÁ
    public function store(Request $request)
    {
        $request->validate([
            'HoTen' => 'required|max:100',
            'SoDienThoai' => 'required|regex:/^[0-9]{10}$/',
            'DiaChi' => 'required|max:255',
            'PTThanhToan' => 'required|in:COD,Bank,VNPay',
        ], [
            'HoTen.required' => 'Vui lòng nhập họ tên',
            'SoDienThoai.required' => 'Vui lòng nhập số điện thoại',
            'SoDienThoai.regex' => 'Số điện thoại phải có 10 chữ số',
            'DiaChi.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'PTThanhToan.required' => 'Vui lòng chọn phương thức thanh toán',
        ]);

        // Cập nhật số điện thoại vào user nếu chưa có
        $user = Auth::user();
        if (empty($user->SoDienThoai) || $user->SoDienThoai != $request->SoDienThoai) {
            $user->SoDienThoai = $request->SoDienThoai;
            $user->save();
        }

        $itemIds = $request->input('items', []);
        
        if (empty($itemIds)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm!');
        }

        // Kiểm tra "Mua ngay" hay "Từ giỏ hàng"
        $isBuyNow = false;
        $buyNowItems = [];
        
        foreach ($itemIds as $itemId) {
            if (strpos($itemId, 'buy_now_') === 0) {
                $isBuyNow = true;
                $maCTSanPham = str_replace('buy_now_', '', $itemId);
                $soLuong = $request->input('so_luong_' . $maCTSanPham, 1);
                $ctSanPham = CTSanPham::with('sanPham')->findOrFail($maCTSanPham);
                
                $item = new \stdClass();
                $item->MaCTSanPham = $ctSanPham->MaCTSanPham;
                $item->SoLuong = $soLuong;
                $item->DonGia = $ctSanPham->DonGia;
                $item->sanPham = $ctSanPham;
                
                $buyNowItems[] = $item;
            }
        }

        if ($isBuyNow) {
            $gioHang = collect($buyNowItems);
        } else {
            $gioHang = CTGioHang::with('sanPham')
                ->where('MaNguoiDung', Auth::id())
                ->whereIn('MaCTGioHang', $itemIds)
                ->get();
        }

        if ($gioHang->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            // Kiểm tra tồn kho
            foreach ($gioHang as $item) {
                if ($item->sanPham->SoLuongTon < $item->SoLuong) {
                    DB::rollBack();
                    return back()->with('error', 'Sản phẩm ' . $item->sanPham->tenDayDu() . ' không đủ số lượng!');
                }
            }

            // Tính tổng tiền ban đầu
            $tongTien = $gioHang->sum(function($item) {
                return $item->SoLuong * $item->DonGia;
            });

            // ============================================
            // XỬ LÝ MÃ GIẢM GIÁ - ĐÃ SỬA
            // ============================================
            $soTienGiam = 0;
            $maMaGiamGia = null;

            if (session()->has('applied_coupon')) {
                $appliedCoupon = session('applied_coupon');
                $maGiamGia = MaGiamGia::find($appliedCoupon['MaMaGiamGia']);
                
                if (!$maGiamGia) {
                    // Mã không tồn tại
                    DB::rollBack();
                    session()->forget('applied_coupon');
                    return redirect()->route('cart.index')
                        ->with('error', 'Mã giảm giá không còn tồn tại. Vui lòng kiểm tra lại giỏ hàng!');
                }
                
                // Kiểm tra lại tính hợp lệ (bao gồm giới hạn người dùng)
                $validation = $maGiamGia->kiemTraHopLe(Auth::id());
                
                if (!$validation['valid']) {
                    // Mã không hợp lệ (hết hạn, hết lượt, v.v.)
                    DB::rollBack();
                    session()->forget('applied_coupon');
                    return redirect()->route('cart.index')
                        ->with('error', 'Mã giảm giá không còn hợp lệ: ' . $validation['message']);
                }
                
                // Tính lại giảm giá với tổng tiền hiện tại
                $result = $maGiamGia->tinhTienGiam($tongTien);
                
                if (!$result['success']) {
                    // Không đủ điều kiện đơn tối thiểu
                    DB::rollBack();
                    session()->forget('applied_coupon');
                    return redirect()->route('cart.index')
                        ->with('error', 'Không thể áp dụng mã giảm giá: ' . $result['message']);
                }
                
                // OK - Áp dụng mã
                $soTienGiam = $result['discount'];
                $maMaGiamGia = $maGiamGia->MaMaGiamGia;
                
                // Giảm tổng tiền
                $tongTien -= $soTienGiam;
            }

            // Tạo đơn hàng
            $donHang = DonHang::create([
                'MaNguoiDung' => Auth::id(),
                'NgayDat' => Carbon::now(),
                'TongTien' => $tongTien,
                'MaMaGiamGia' => $maMaGiamGia,
                'SoTienGiam' => $soTienGiam,
                'DiaChiGiaoHang' => $request->DiaChi,
                'PTThanhToan' => $request->PTThanhToan,
                'TrangThai' => 'Chờ xác nhận',
            ]);

            // Tạo chi tiết đơn hàng và trừ tồn kho
            foreach ($gioHang as $item) {
                CTDonHang::create([
                    'MaDonHang' => $donHang->MaDonHang,
                    'MaCTSanPham' => $item->MaCTSanPham,
                    'SoLuong' => $item->SoLuong,
                    'DonGia' => $item->DonGia,
                    'ThanhTien' => $item->SoLuong * $item->DonGia,
                ]);

                $item->sanPham->decrement('SoLuongTon', $item->SoLuong);
            }

            // ============================================
            // LƯU LỊCH SỬ SỬ DỤNG MÃ GIẢM GIÁ
            // ============================================
            if ($maMaGiamGia && $soTienGiam > 0) {
                $maGiamGia = MaGiamGia::find($maMaGiamGia);
                
                // Tăng số lần sử dụng
                $maGiamGia->tangSoLanSuDung();
                
                // Lưu lịch sử
                LichSuMaGiamGia::create([
                    'MaMaGiamGia' => $maMaGiamGia,
                    'MaNguoiDung' => Auth::id(),
                    'MaDonHang' => $donHang->MaDonHang,
                    'SoTienGiam' => $soTienGiam,
                    'ThoiGianSuDung' => Carbon::now()
                ]);
                
                // Xóa mã khỏi session
                session()->forget('applied_coupon');
            }

            // Xóa giỏ hàng (chỉ nếu không phải mua ngay)
            if (!$isBuyNow) {
                CTGioHang::where('MaNguoiDung', Auth::id())
                    ->whereIn('MaCTGioHang', $itemIds)
                    ->delete();
            }
            
            DB::commit();

            // Chuyển hướng
            if ($request->PTThanhToan == 'VNPay') {
                return redirect()->route('vnpay.simulate', $donHang->MaDonHang);
            }

            return redirect()->route('orders.success', $donHang->MaDonHang)
                           ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Trang đặt hàng thành công
    public function success($id)
    {
        $donHang = DonHang::with(['chiTiet.sanPham.sanPham'])
            ->where('MaNguoiDung', Auth::id())
            ->findOrFail($id);

        return view('orders.success', compact('donHang'));
    }

    // Lịch sử đơn hàng
    public function history()
    {
        $donHang = DonHang::with('chiTiet')
            ->where('MaNguoiDung', Auth::id())
            ->latest('NgayDat')
            ->paginate(10);

        return view('orders.history', compact('donHang'));
    }

    // Chi tiết đơn hàng
    public function detail($id)
    {
        $donHang = DonHang::with(['chiTiet.sanPham.sanPham'])
            ->where('MaNguoiDung', Auth::id())
            ->findOrFail($id);

        return view('orders.detail', compact('donHang'));
    }

    // Hủy đơn hàng (người dùng)
    public function cancel($id)
    {
        $donHang = DonHang::where('MaNguoiDung', Auth::id())->findOrFail($id);
        
        if ($donHang->TrangThai !== 'Chờ xác nhận') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xác nhận". Vui lòng liên hệ shop để hỗ trợ!');
        }
        
        DB::beginTransaction();
        try {
            foreach ($donHang->chiTiet as $chiTiet) {
                $chiTiet->sanPham->increment('SoLuongTon', $chiTiet->SoLuong);
            }
            
            $donHang->update([
                'TrangThai' => 'Đã hủy',
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Đã hủy đơn hàng thành công!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Giả lập thanh toán VNPay
    public function vnpaySimulate($id)
    {
        $donHang = DonHang::where('MaNguoiDung', Auth::id())->findOrFail($id);
        return view('orders.vnpay-simulate', compact('donHang'));
    }

    // Xác nhận thanh toán VNPay
    public function vnpayConfirm($id)
    {
        $donHang = DonHang::where('MaNguoiDung', Auth::id())->findOrFail($id);
        return redirect()->route('orders.success', $id)->with('success', 'Thanh toán VNPay thành công!');
    }
}