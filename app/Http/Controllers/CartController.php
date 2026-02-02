<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\CTGioHang;
use App\Models\CTSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartController extends Controller
{
    // ============================================
    // ĐỊNH NGHĨA SỐ LƯỢNG TỐI ĐA CHO MỖI SẢN PHẨM
    // ============================================
    const MAX_QUANTITY = 20;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Xem giỏ hàng
    public function index()
    {
        $gioHang = CTGioHang::with('sanPham.sanPham.bienThe')
            ->where('MaNguoiDung', Auth::id())
            ->get();

        $tongTien = $gioHang->sum(function($item) {
            return $item->SoLuong * $item->DonGia;
        });

        // ============================================
        // KIỂM TRA LẠI MÃ GIẢM GIÁ (QUAN TRỌNG!)
        // ============================================
        if (session('applied_coupon')) {
            $maGiamGia = \App\Models\MaGiamGia::find(session('applied_coupon')['MaMaGiamGia']);
            
            if (!$maGiamGia) {
                // Mã không tồn tại → Xóa
                session()->forget('applied_coupon');
                session()->flash('warning', 'Mã giảm giá không còn tồn tại và đã được xóa khỏi đơn hàng.');
            } else {
                // Kiểm tra tính hợp lệ
                $validation = $maGiamGia->kiemTraHopLe();
                
                if (!$validation['valid']) {
                    // Mã không hợp lệ → Xóa
                    session()->forget('applied_coupon');
                    session()->flash('warning', 'Mã giảm giá không còn hợp lệ: ' . $validation['message']);
                } else {
                    // Kiểm tra điều kiện đơn tối thiểu
                    $result = $maGiamGia->tinhTienGiam($tongTien);
                    
                    if (!$result['success']) {
                        // Không đủ điều kiện → Xóa
                        session()->forget('applied_coupon');
                        session()->flash('warning', 'Mã giảm giá đã bị hủy: ' . $result['message']);
                    } else {
                        // Cập nhật lại số tiền giảm (nếu tổng tiền thay đổi)
                        session(['applied_coupon' => [
                            'MaMaGiamGia' => $maGiamGia->MaMaGiamGia,
                            'MaCode' => $maGiamGia->MaCode,
                            'SoTienGiam' => $result['discount']
                        ]]);
                    }
                }
            }
        }

        return view('cart.index', compact('gioHang', 'tongTien'));
    }

    // ============================================
    // Thêm vào giỏ hàng - CÓ GIỚI HẠN MAX 20
    // ============================================
    public function add(Request $request)
    {
        $request->validate([
            'MaCTSanPham' => 'required|exists:ct_san_pham,MaCTSanPham',
            'SoLuong' => 'required|integer|min:1|max:' . self::MAX_QUANTITY,
        ], [
            'SoLuong.max' => 'Số lượng tối đa cho mỗi sản phẩm là ' . self::MAX_QUANTITY,
        ]);

        $sanPham = CTSanPham::findOrFail($request->MaCTSanPham);

        // Kiểm tra tồn kho
        if ($sanPham->SoLuongTon < $request->SoLuong) {
            return back()->with('error', 'Sản phẩm không đủ số lượng trong kho!');
        }

        // Kiểm tra đã có trong giỏ hàng chưa
        $gioHangItem = CTGioHang::where('MaNguoiDung', Auth::id())
            ->where('MaCTSanPham', $request->MaCTSanPham)
            ->first();

        if ($gioHangItem) {
            // Cập nhật số lượng
            $soLuongMoi = $gioHangItem->SoLuong + $request->SoLuong;
            
            // KIỂM TRA GIỚI HẠN TỐI ĐA
            if ($soLuongMoi > self::MAX_QUANTITY) {
                return back()->with('error', "Số lượng tối đa cho mỗi sản phẩm là " . self::MAX_QUANTITY . ". Bạn đã có {$gioHangItem->SoLuong} sản phẩm trong giỏ!");
            }
            
            if ($sanPham->SoLuongTon < $soLuongMoi) {
                return back()->with('error', 'Sản phẩm không đủ số lượng trong kho!');
            }

            $gioHangItem->update([
                'SoLuong' => $soLuongMoi,
                'buy_now' => $request->buy_now ?? false,
            ]);
        } else {
            // Thêm mới
            $gioHangItem = CTGioHang::create([
                'MaNguoiDung' => Auth::id(),
                'MaCTSanPham' => $request->MaCTSanPham,
                'SoLuong' => $request->SoLuong,
                'DonGia' => $sanPham->DonGia,
                'NgayThem' => Carbon::now(),
                'buy_now' => $request->buy_now ?? false,
            ]);
        }

        // Nếu là mua ngay → redirect thẳng checkout
        if ($request->buy_now) {
            return redirect()->route('orders.checkout', [
                'MaCTSanPham' => $gioHangItem->MaCTSanPham,
                'SoLuong' => $gioHangItem->SoLuong
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // ============================================
    // Cập nhật số lượng qua AJAX - CÓ GIỚI HẠN MAX 20
    // ============================================
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'SoLuong' => 'required|integer|min:1|max:' . self::MAX_QUANTITY,
            ], [
                'SoLuong.max' => 'Số lượng tối đa là ' . self::MAX_QUANTITY,
            ]);

            $gioHangItem = CTGioHang::where('MaNguoiDung', Auth::id())
                ->where('MaCTGioHang', $id)
                ->firstOrFail();

            $sanPham = $gioHangItem->sanPham;

            // Kiểm tra tồn kho
            if ($sanPham->SoLuongTon < $request->SoLuong) {
                return response()->json([
                    'success' => false,
                    'message' => "Sản phẩm chỉ còn {$sanPham->SoLuongTon} sản phẩm trong kho!"
                ]);
            }

            // Cập nhật số lượng
            $gioHangItem->update([
                'SoLuong' => $request->SoLuong,
            ]);

            // Tính lại thành tiền
            $itemTotal = $gioHangItem->thanhTien();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng!',
                'newQuantity' => $request->SoLuong,
                'itemTotal' => $itemTotal,
                'itemPrice' => $gioHangItem->DonGia
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // Cập nhật biến thể - KIỂM TRA GIỚI HẠN MAX 20
    // ============================================
    public function updateVariant(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $gioHangItem = CTGioHang::where('MaNguoiDung', Auth::id())
                ->where('MaCTGioHang', $id)
                ->firstOrFail();

            $sanPhamCu = $gioHangItem->sanPham;
            $maSanPham = $sanPhamCu->MaSanPham;
            $soLuongHienTai = $gioHangItem->SoLuong;

            // Tìm biến thể mới dựa trên màu, size, dung lượng
            $query = CTSanPham::where('MaSanPham', $maSanPham)
                ->where('TrangThai', 1);

            if ($request->color) {
                $query->where('MauSac', $request->color);
            }
            if ($request->size) {
                $query->where('KichThuoc', $request->size);
            }
            if ($request->capacity) {
                $query->where('DungLuong', $request->capacity);
            }

            $bienTheMoi = $query->first();

            if (!$bienTheMoi) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy biến thể phù hợp!'
                ]);
            }

            // Kiểm tra nếu chọn lại biến thể hiện tại (không làm gì)
            if ($bienTheMoi->MaCTSanPham == $gioHangItem->MaCTSanPham) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'noChange' => true,
                    'message' => 'Biến thể không thay đổi'
                ]);
            }

            // Kiểm tra tồn kho của biến thể mới
            if ($bienTheMoi->SoLuongTon < $soLuongHienTai) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Biến thể này chỉ còn {$bienTheMoi->SoLuongTon} sản phẩm trong kho!"
                ]);
            }

            // Kiểm tra xem biến thể mới đã có trong giỏ hàng chưa
            $existingItem = CTGioHang::where('MaNguoiDung', Auth::id())
                ->where('MaCTSanPham', $bienTheMoi->MaCTSanPham)
                ->where('MaCTGioHang', '!=', $id)
                ->first();

            if ($existingItem) {
                // Nếu đã có, gộp số lượng
                $tongSoLuong = $existingItem->SoLuong + $soLuongHienTai;
                
                // KIỂM TRA GIỚI HẠN TỐI ĐA
                if ($tongSoLuong > self::MAX_QUANTITY) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Tổng số lượng sau khi gộp là {$tongSoLuong} vượt quá giới hạn " . self::MAX_QUANTITY . " sản phẩm!"
                    ]);
                }
                
                if ($bienTheMoi->SoLuongTon < $tongSoLuong) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Biến thể này đã có trong giỏ hàng. Tổng số lượng sau khi gộp là {$tongSoLuong} nhưng chỉ còn {$bienTheMoi->SoLuongTon} sản phẩm!"
                    ]);
                }

                // Gộp số lượng vào item có sẵn và xóa item cũ
                $existingItem->update([
                    'SoLuong' => $tongSoLuong,
                ]);
                $gioHangItem->delete();

                DB::commit();

                // Trả về với flag cần reload trang
                return response()->json([
                    'success' => true,
                    'merged' => true,
                    'message' => 'Đã gộp với sản phẩm cùng loại trong giỏ hàng!'
                ]);
            } else {
                // Cập nhật sang biến thể mới
                $gioHangItem->update([
                    'MaCTSanPham' => $bienTheMoi->MaCTSanPham,
                    'DonGia' => $bienTheMoi->DonGia,
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'merged' => false,
                    'newPrice' => $bienTheMoi->DonGia,
                    'newStock' => $bienTheMoi->SoLuongTon,
                    'newImage' => $bienTheMoi->AnhMinhHoa,
                    'itemTotal' => $bienTheMoi->DonGia * $soLuongHienTai,
                    'message' => 'Đã cập nhật biến thể!'
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xóa khỏi giỏ hàng
    public function remove($id)
    {
        $gioHangItem = CTGioHang::where('MaNguoiDung', Auth::id())
            ->where('MaCTGioHang', $id)
            ->firstOrFail();

        $gioHangItem->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        CTGioHang::where('MaNguoiDung', Auth::id())->delete();
        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}