<?php

namespace App\Http\Controllers;

use App\Models\MaGiamGia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MaGiamGiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['apDung', 'huyMa']);
    }

    // Hiển thị danh sách mã giảm giá (Admin)
    public function index()
    {
        $maGiamGia = MaGiamGia::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.coupons.index', compact('maGiamGia'));
    }

    // Form tạo mã giảm giá mới
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Lưu mã giảm giá mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaCode' => 'required|string|unique:ma_giam_gia,MaCode|max:50',
            'LoaiGiam' => 'required|in:fixed,percent',
            'GiaTri' => 'required|numeric|min:0',
            'DonToiThieu' => 'nullable|numeric|min:0',
            'GiamToiDa' => 'nullable|numeric|min:0',
            'GioiHanSuDung' => 'nullable|integer|min:1',
            'GioiHanMoiNguoi' => 'nullable|integer|min:1',
            'NgayBatDau' => 'nullable|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayBatDau',
            'TrangThai' => 'boolean',
            'MoTa' => 'nullable|string'
        ], [
            'MaCode.required' => 'Vui lòng nhập mã giảm giá',
            'MaCode.unique' => 'Mã giảm giá đã tồn tại',
            'LoaiGiam.required' => 'Vui lòng chọn loại giảm giá',
            'GiaTri.required' => 'Vui lòng nhập giá trị',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'GioiHanMoiNguoi.min' => 'Giới hạn mỗi người phải ít nhất 1'
        ]);

        $validated['MaCode'] = strtoupper($validated['MaCode']);

        $validated['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        MaGiamGia::create($validated);

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Tạo mã giảm giá thành công!');
    }

    // Form chỉnh sửa mã giảm giá
    public function edit($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        return view('admin.coupons.edit', compact('maGiamGia'));
    }

    // Cập nhật mã giảm giá
    public function update(Request $request, $id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);

        $validated = $request->validate([
            'MaCode' => 'required|string|max:50|unique:ma_giam_gia,MaCode,' . $id . ',MaMaGiamGia',
            'LoaiGiam' => 'required|in:fixed,percent',
            'GiaTri' => 'required|numeric|min:0',
            'DonToiThieu' => 'nullable|numeric|min:0',
            'GiamToiDa' => 'nullable|numeric|min:0',
            'GioiHanSuDung' => 'nullable|integer|min:1',
            'GioiHanMoiNguoi' => 'nullable|integer|min:1',
            'NgayBatDau' => 'nullable|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayBatDau',
            'TrangThai' => 'boolean',
            'MoTa' => 'nullable|string'
        ]);

        $validated['MaCode'] = strtoupper($validated['MaCode']);

        $validated['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        $maGiamGia->update($validated);

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    // Xóa mã giảm giá
    public function destroy($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        $maGiamGia->delete();
        
        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Xóa mã giảm giá thành công!');
    }

    // API: Áp dụng mã giảm giá (Frontend - Cart)
    public function apDung(Request $request)
    {
        $request->validate([
            'MaCode' => 'required|string',
            'TongTien' => 'required|numeric|min:0'
        ]);

        $maGiamGia = MaGiamGia::where('MaCode', strtoupper($request->MaCode))->first();

        if (!$maGiamGia) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại'
            ], 404);
        }

        // Kiểm tra tính hợp lệ
        $validation = $maGiamGia->kiemTraHopLe(auth()->id());
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 400);
        }

        // Tính toán giảm giá
        $result = $maGiamGia->tinhTienGiam($request->TongTien);

        if ($result['success']) {
            // Lưu mã vào session
            session(['applied_coupon' => [
                'MaMaGiamGia' => $maGiamGia->MaMaGiamGia,
                'MaCode' => $maGiamGia->MaCode,
                'SoTienGiam' => $result['discount'],
                'LoaiGiam' => $maGiamGia->LoaiGiam,        // THÊM
                'GiaTri' => $maGiamGia->GiaTri,            // THÊM
                'GiamToiDa' => $maGiamGia->GiamToiDa,      // THÊM
                'DonToiThieu' => $maGiamGia->DonToiThieu   // THÊM (optional)
            ]]);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'discount' => $result['discount'],
                'coupon' => [
                    'MaMaGiamGia' => $maGiamGia->MaMaGiamGia,
                    'MaCode' => $maGiamGia->MaCode,
                    'LoaiGiam' => $maGiamGia->LoaiGiam,
                    'GiaTri' => $maGiamGia->GiaTri
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 400);
    }

    // Hủy áp dụng mã giảm giá
    public function huyMa()
    {
        session()->forget('applied_coupon');
        
        return response()->json([
            'success' => true,
            'message' => 'Đã hủy mã giảm giá'
        ]);
    }
    // Xem lịch sử sử dụng mã giảm giá
    public function lichSu($id)
    {
        $maGiamGia = MaGiamGia::with([
            'lichSuSuDung' => function($query) {
                $query->with(['nguoiDung', 'donHang'])
                    ->orderBy('ThoiGianSuDung', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.coupons.history', compact('maGiamGia'));
    }
}