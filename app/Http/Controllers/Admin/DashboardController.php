<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\CTDonHang;
use App\Models\SanPham;
use App\Models\CTSanPham;
use App\Models\NguoiDung;
use App\Models\PhanHoi;
use App\Models\BinhLuan;
use App\Models\DanhGia;
use App\Models\DanhMuc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ============ XỬ LÝ FILTER THỜI GIAN ============
        
        $filter = $request->get('filter', '30');
        $tuNgay = $request->get('tu_ngay');
        $denNgay = $request->get('den_ngay');
        
        // Xác định khoảng thời gian
        if ($filter == 'custom' && $tuNgay && $denNgay) {
            $startDate = Carbon::parse($tuNgay)->startOfDay();
            $endDate = Carbon::parse($denNgay)->endOfDay();
        } else {
            $endDate = Carbon::now()->endOfDay();
            switch ($filter) {
                case 'today':
                    $startDate = Carbon::today()->startOfDay();
                    break;
                case '7':
                    $startDate = Carbon::now()->subDays(6)->startOfDay();
                    break;
                case '30':
                    $startDate = Carbon::now()->subDays(29)->startOfDay();
                    break;
                case '90':
                    $startDate = Carbon::now()->subDays(89)->startOfDay();
                    break;
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = Carbon::now()->startOfYear();
                    break;
                case 'last_year':
                    $startDate = Carbon::now()->subYear()->startOfYear();
                    $endDate = Carbon::now()->subYear()->endOfYear();
                    break;
                default:
                    $startDate = Carbon::now()->subDays(29)->startOfDay();
            }
        }
        
        // ============ THỐNG KÊ TỔNG QUAN ============
        
        // Tổng sản phẩm
        $tongSanPham = SanPham::count();
        
        // Đơn hàng trong khoảng thời gian
        $tongDonHang = DonHang::whereBetween('NgayDat', [$startDate, $endDate])->count();
        
        // Doanh thu trong khoảng thời gian (chỉ tính đơn đã giao)
        $tongDoanhThu = DonHang::where('TrangThai', 'Đã giao hàng')
            ->whereBetween('NgayDat', [$startDate, $endDate])
            ->sum('TongTien');
        
        // Giá trị đơn hàng trung bình
        $giaTriDonTrungBinh = $tongDonHang > 0 ? $tongDoanhThu / $tongDonHang : 0;
        
        // ============ THỐNG KÊ KHÁCH HÀNG ============
        
        // Khách hàng mới trong khoảng thời gian
        $khachHangMoi = NguoiDung::where('VaiTro', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Khách hàng đã mua hàng trong khoảng thời gian
        $khachHangDaMua = DonHang::whereBetween('NgayDat', [$startDate, $endDate])
            ->distinct('MaNguoiDung')
            ->count('MaNguoiDung');
        
        // Tổng số khách hàng trong hệ thống
        $tongKhachHang = NguoiDung::where('VaiTro', 0)->count();
        
        // Khách hàng quay lại (mua >= 2 đơn)
        $khachHangQuayLai = DonHang::select('MaNguoiDung')
            ->whereBetween('NgayDat', [$startDate, $endDate])
            ->groupBy('MaNguoiDung')
            ->havingRaw('COUNT(*) >= 2')
            ->count();
        
        // ============ THÔNG BÁO ============
        
        // Đơn hàng chờ xác nhận
        $donHangChoXacNhan = DonHang::where('TrangThai', 'Chờ xác nhận')->count();
        
        // Phản hồi chưa xem
        $phanHoiChuaXem = PhanHoi::where('TrangThai', 0)->count();
        
        // Bình luận chưa đọc
        $binhLuanMoi = BinhLuan::where('DaXem', 0)->count();
        
        // Đánh giá chưa xem
        $danhGiaChuaXem = DanhGia::where('DaXem', 0)->count();
        
        // Sản phẩm sắp hết hàng
        $sanPhamSapHet = CTSanPham::where('SoLuongTon', '>', 0)
            ->where('SoLuongTon', '<=', 10)
            ->where('TrangThai', 1)
            ->count();
        
        // ============ SO SÁNH VỚI KHOẢNG TRƯỚC ============
        
        $soNgay = $startDate->diffInDays($endDate) + 1;
        $previousStartDate = $startDate->copy()->subDays($soNgay);
        $previousEndDate = $startDate->copy()->subDay();
        
        // Doanh thu kỳ trước
        $doanhThuTruoc = DonHang::where('TrangThai', 'Đã giao hàng')
            ->whereBetween('NgayDat', [$previousStartDate, $previousEndDate])
            ->sum('TongTien');
        
        $phanTramDoanhThu = $doanhThuTruoc > 0 
            ? (($tongDoanhThu - $doanhThuTruoc) / $doanhThuTruoc) * 100 
            : ($tongDoanhThu > 0 ? 100 : 0);
        
        // Đơn hàng kỳ trước
        $donHangTruoc = DonHang::whereBetween('NgayDat', [$previousStartDate, $previousEndDate])->count();
        $phanTramDonHang = $donHangTruoc > 0 
            ? (($tongDonHang - $donHangTruoc) / $donHangTruoc) * 100 
            : ($tongDonHang > 0 ? 100 : 0);
        
        // Khách hàng mới kỳ trước
        $khachHangMoiTruoc = NguoiDung::where('VaiTro', 0)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();
        $phanTramKhachHang = $khachHangMoiTruoc > 0 
            ? (($khachHangMoi - $khachHangMoiTruoc) / $khachHangMoiTruoc) * 100 
            : ($khachHangMoi > 0 ? 100 : 0);
        
        // ============ THỐNG KÊ ĐƠN HÀNG THEO TRẠNG THÁI ============
        
        $thongKeDonHang = DonHang::select('TrangThai', DB::raw('count(*) as SoLuong'))
            ->whereBetween('NgayDat', [$startDate, $endDate])
            ->groupBy('TrangThai')
            ->get()
            ->pluck('SoLuong', 'TrangThai');
        
        // ============ BIỂU ĐỒ DOANH THU THEO NGÀY ============
        
        $bieuDoDoanhThu = DonHang::where('TrangThai', 'Đã giao hàng')
            ->whereBetween('NgayDat', [$startDate, $endDate])
            ->selectRaw('DATE(NgayDat) as Ngay, SUM(TongTien) as DoanhThu, COUNT(*) as SoDon')
            ->groupBy('Ngay')
            ->orderBy('Ngay')
            ->get();
        
        // ============ DOANH THU THEO DANH MỤC ============
        
        $doanhThuTheoDanhMuc = CTDonHang::join('don_hang', 'ct_don_hang.MaDonHang', '=', 'don_hang.MaDonHang')
            ->join('ct_san_pham', 'ct_don_hang.MaCTSanPham', '=', 'ct_san_pham.MaCTSanPham')
            ->join('san_pham', 'ct_san_pham.MaSanPham', '=', 'san_pham.MaSanPham')
            ->join('danh_muc', 'san_pham.MaDanhMuc', '=', 'danh_muc.MaDanhMuc')
            ->where('don_hang.TrangThai', 'Đã giao hàng')
            ->whereBetween('don_hang.NgayDat', [$startDate, $endDate])
            ->select(
                'danh_muc.TenDanhMuc',
                DB::raw('SUM(ct_don_hang.ThanhTien) as TongDoanhThu')
            )
            ->groupBy('danh_muc.MaDanhMuc', 'danh_muc.TenDanhMuc')
            ->orderByDesc('TongDoanhThu')
            ->get();
        
        // ============ TOP SẢN PHẨM BÁN CHẠY ============
        
        $sanPhamBanChay = CTDonHang::join('don_hang', 'ct_don_hang.MaDonHang', '=', 'don_hang.MaDonHang')
            ->join('ct_san_pham', 'ct_don_hang.MaCTSanPham', '=', 'ct_san_pham.MaCTSanPham')
            ->join('san_pham', 'ct_san_pham.MaSanPham', '=', 'san_pham.MaSanPham')
            ->where('don_hang.TrangThai', 'Đã giao hàng')
            ->whereBetween('don_hang.NgayDat', [$startDate, $endDate])
            ->select(
                'san_pham.MaSanPham',
                'san_pham.TenSanPham',
                'san_pham.AnhChinh',
                DB::raw('SUM(ct_don_hang.SoLuong) as TongSoLuong'),
                DB::raw('SUM(ct_don_hang.ThanhTien) as TongDoanhThu')
            )
            ->groupBy('san_pham.MaSanPham', 'san_pham.TenSanPham', 'san_pham.AnhChinh')
            ->orderByDesc('TongSoLuong')
            ->take(5)
            ->get();
        
        // ============ SẢN PHẨM BÁN CHẬM ============
        
        $sanPhamBanCham = CTDonHang::join('don_hang', 'ct_don_hang.MaDonHang', '=', 'don_hang.MaDonHang')
            ->join('ct_san_pham', 'ct_don_hang.MaCTSanPham', '=', 'ct_san_pham.MaCTSanPham')
            ->join('san_pham', 'ct_san_pham.MaSanPham', '=', 'san_pham.MaSanPham')
            ->where('don_hang.TrangThai', 'Đã giao hàng')
            ->whereBetween('don_hang.NgayDat', [$startDate, $endDate])
            ->select(
                'san_pham.MaSanPham',
                'san_pham.TenSanPham',
                DB::raw('SUM(ct_don_hang.SoLuong) as TongSoLuong')
            )
            ->groupBy('san_pham.MaSanPham', 'san_pham.TenSanPham')
            ->havingRaw('TongSoLuong <= 5')
            ->orderBy('TongSoLuong')
            ->take(5)
            ->get();
        
        // ============ SẢN PHẨM KHÔNG BÁN ĐƯỢC ============
        
        $sanPhamKhongBan = SanPham::whereNotIn('MaSanPham', function($query) use ($startDate, $endDate) {
                $query->select('ct_san_pham.MaSanPham')
                    ->from('ct_don_hang')
                    ->join('don_hang', 'ct_don_hang.MaDonHang', '=', 'don_hang.MaDonHang')
                    ->join('ct_san_pham', 'ct_don_hang.MaCTSanPham', '=', 'ct_san_pham.MaCTSanPham')
                    ->where('don_hang.TrangThai', 'Đã giao hàng')
                    ->whereBetween('don_hang.NgayDat', [$startDate, $endDate]);
            })
            ->where('TrangThai', 1)
            ->take(5)
            ->get();
        
        // ============ SẢN PHẨM SẮP HẾT HÀNG (CHI TIẾT) ============
        
        $sanPhamSapHetChiTiet = CTSanPham::join('san_pham', 'ct_san_pham.MaSanPham', '=', 'san_pham.MaSanPham')
            ->where('ct_san_pham.SoLuongTon', '>', 0)
            ->where('ct_san_pham.SoLuongTon', '<=', 10)
            ->where('ct_san_pham.TrangThai', 1)
            ->select('san_pham.MaSanPham', 'san_pham.TenSanPham', 'ct_san_pham.MauSac', 'ct_san_pham.SoLuongTon')
            ->orderBy('ct_san_pham.SoLuongTon')
            ->take(5)
            ->get();
        
        // ============ ĐƠN HÀNG GẦN ĐÂY ============
        
        $donHangGanDay = DonHang::with('nguoiDung')
            ->latest('NgayDat')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'tongSanPham',
            'tongDonHang',
            'tongDoanhThu',
            'giaTriDonTrungBinh',
            'khachHangMoi',
            'khachHangDaMua',
            'tongKhachHang',
            'khachHangQuayLai',
            'donHangChoXacNhan',
            'phanHoiChuaXem',
            'binhLuanMoi',
            'danhGiaChuaXem',
            'sanPhamSapHet',
            'phanTramDoanhThu',
            'phanTramDonHang',
            'phanTramKhachHang',
            'thongKeDonHang',
            'bieuDoDoanhThu',
            'doanhThuTheoDanhMuc',
            'sanPhamBanChay',
            'sanPhamBanCham',
            'sanPhamKhongBan',
            'sanPhamSapHetChiTiet',
            'donHangGanDay',
            'filter',
            'tuNgay',
            'denNgay',
            'startDate',
            'endDate'
        ));
    }
}