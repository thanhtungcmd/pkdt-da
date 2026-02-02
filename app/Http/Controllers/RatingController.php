<?php
// app/Http/Controllers/RatingController.php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Form đánh giá sản phẩm
    public function create(Request $request)
    {
        $maSanPham = $request->san_pham;
        
        // Kiểm tra đã mua sản phẩm chưa
        $donHang = DonHang::where('MaNguoiDung', Auth::id())
            ->where('TrangThai', 'đã giao hàng')
            ->whereHas('chiTiet.sanPham.sanPham', function($q) use ($maSanPham) {
                $q->where('MaSanPham', $maSanPham);
            })
            ->first();
        
        if (!$donHang) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sau khi mua hàng!');
        }
        
        // Kiểm tra đã đánh giá chưa
        $daDanhGia = DanhGia::where('MaNguoiDung', Auth::id())
            ->where('MaSanPham', $maSanPham)
            ->where('MaDonHang', $donHang->MaDonHang)
            ->exists();
        
        if ($daDanhGia) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }
        
        return view('ratings.create', compact('maSanPham', 'donHang'));
    }

    // Lưu đánh giá
    public function store(Request $request)
    {
        $request->validate([
            'MaSanPham' => 'required|exists:san_pham,MaSanPham',
            'MaDonHang' => 'required|exists:don_hang,MaDonHang',
            'SoSao' => 'required|integer|min:1|max:5',
            'NoiDung' => 'nullable|min:10|max:1000',
            'HinhAnh.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Mỗi ảnh tối đa 2MB
        ], [
            'SoSao.required' => 'Vui lòng chọn số sao',
            'SoSao.min' => 'Số sao tối thiểu là 1',
            'SoSao.max' => 'Số sao tối đa là 5',
            'NoiDung.min' => 'Nội dung tối thiểu 10 ký tự',
            'NoiDung.max' => 'Nội dung tối đa 1000 ký tự',
            'HinhAnh.*.image' => 'File phải là ảnh',
            'HinhAnh.*.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png',
            'HinhAnh.*.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        // Kiểm tra lại đã mua hàng chưa
        $donHang = DonHang::where('MaDonHang', $request->MaDonHang)
            ->where('MaNguoiDung', Auth::id())
            ->where('TrangThai', 'đã giao hàng')
            ->firstOrFail();
        
        // Kiểm tra sản phẩm có trong đơn hàng không
        $coSanPham = $donHang->chiTiet()  //chiTietDonHangs()
            ->whereHas('sanPham.sanPham', function($q) use ($request) {//chiTietSanPham.sanPham
                $q->where('MaSanPham', $request->MaSanPham);
            })
            ->exists();
        
        if (!$coSanPham) {
            return back()->with('error', 'Sản phẩm không có trong đơn hàng này!');
        }
        
        // Kiểm tra đã đánh giá chưa
        $daDanhGia = DanhGia::where('MaNguoiDung', Auth::id())
            ->where('MaSanPham', $request->MaSanPham)
            ->where('MaDonHang', $request->MaDonHang)
            ->exists();
        
        if ($daDanhGia) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        // Xử lý upload ảnh
        $hinhAnhPaths = [];
        if ($request->hasFile('HinhAnh')) {
            foreach ($request->file('HinhAnh') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/reviews'), $filename);
                $hinhAnhPaths[] = 'uploads/reviews/' . $filename;
            }
        }

        // Tạo đánh giá
        DanhGia::create([
            'MaNguoiDung' => Auth::id(),
            'MaSanPham' => $request->MaSanPham,
            'MaDonHang' => $request->MaDonHang,
            'SoSao' => $request->SoSao,
            'NoiDung' => $request->NoiDung,
            'HinhAnh' => !empty($hinhAnhPaths) ? $hinhAnhPaths : null,
            'TrangThai' => 'da_duyet', // Mặc định đã duyệt
            'NgayDanhGia' => Carbon::now(),
        ]);

        return redirect()->route('products.show', $request->MaSanPham)
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    // Xóa đánh giá (chỉ người viết)
    public function destroy($id)
    {
        $danhGia = DanhGia::where('MaNguoiDung', Auth::id())
            ->findOrFail($id);

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

    // Vote đánh giá hữu ích/không hữu ích
    public function voteHelpful(Request $request, $id)
    {
        $request->validate([
            'huu_ich' => 'required|boolean',
        ]);

        $danhGia = DanhGia::findOrFail($id);

        // Kiểm tra đã vote chưa
        $vote = \App\Models\DanhGiaHuuIch::where('MaDanhGia', $id)
            ->where('MaNguoiDung', Auth::id())
            ->first();

        if ($vote) {
            // Nếu đã vote rồi, cập nhật vote
            if ($vote->HuuIch == $request->huu_ich) {
                // Click lại thì xóa vote (toggle)
                $vote->delete();
                return back()->with('success', 'Đã hủy đánh giá!');
            } else {
                // Đổi từ like sang dislike hoặc ngược lại
                $vote->update(['HuuIch' => $request->huu_ich]);
                return back()->with('success', 'Đã cập nhật đánh giá!');
            }
        } else {
            // Chưa vote thì tạo mới
            \App\Models\DanhGiaHuuIch::create([
                'MaDanhGia' => $id,
                'MaNguoiDung' => Auth::id(),
                'HuuIch' => $request->huu_ich,
            ]);
            return back()->with('success', 'Cảm ơn đánh giá của bạn!');
        }
    }
}