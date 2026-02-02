<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\DanhMuc;
use App\Models\SanPham;
use App\Models\BinhLuan;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Danh sách sản phẩm
    public function index(Request $request)
    {
        $danhMuc = DanhMuc::hienThi()->get();
        
        $query = SanPham::with(['danhMuc', 'bienThe', 'danhGia'])->hienThi();

        // Lọc theo danh mục
        if ($request->has('danh_muc') && $request->danh_muc != '') {
            $query->where('MaDanhMuc', $request->danh_muc);
        }
        
        // Lọc theo thương hiệu
        if ($request->has('thuong_hieu') && $request->thuong_hieu != '') {
            $query->where('ThuongHieu', $request->thuong_hieu);
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $query->where('TenSanPham', 'like', '%' . $request->search . '%');
        }

        // Lọc theo khoảng giá
        if ($request->filled('gia_tu') || $request->filled('gia_den')) {
            $query->whereHas('bienThe', function($q) use ($request) {
                if ($request->filled('gia_tu')) {
                    $q->where('DonGia', '>=', $request->gia_tu);
                }
                if ($request->filled('gia_den')) {
                    $q->where('DonGia', '<=', $request->gia_den);
                }
            });
        }

        // Lọc theo đánh giá sao (trung bình)
        if ($request->filled('rating')) {
            $rating = $request->rating;
            $query->whereRaw('(SELECT AVG(SoSao) FROM danh_gia WHERE danh_gia.MaSanPham = san_pham.MaSanPham AND TrangThai = "da_duyet") >= ?', [$rating]);
        }

        // Sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    // Sắp xếp theo giá thấp → cao
                    $query->join('ct_san_pham', 'san_pham.MaSanPham', '=', 'ct_san_pham.MaSanPham')
                          ->select('san_pham.*')
                          ->selectRaw('MIN(ct_san_pham.DonGia) as min_price')
                          ->groupBy('san_pham.MaSanPham', 'san_pham.TenSanPham', 'san_pham.ThuongHieu', 'san_pham.AnhChinh', 'san_pham.MoTa', 'san_pham.TrangThai', 'san_pham.MaDanhMuc', 'san_pham.created_at', 'san_pham.updated_at')
                          ->orderBy('min_price', 'asc');
                    break;
                    
                case 'price_desc':
                    // Sắp xếp theo giá cao → thấp
                    $query->join('ct_san_pham', 'san_pham.MaSanPham', '=', 'ct_san_pham.MaSanPham')
                          ->select('san_pham.*')
                          ->selectRaw('MAX(ct_san_pham.DonGia) as max_price')
                          ->groupBy('san_pham.MaSanPham', 'san_pham.TenSanPham', 'san_pham.ThuongHieu', 'san_pham.AnhChinh', 'san_pham.MoTa', 'san_pham.TrangThai', 'san_pham.MaDanhMuc', 'san_pham.created_at', 'san_pham.updated_at')
                          ->orderBy('max_price', 'desc');
                    break;
                    
                case 'name_asc':
                    $query->orderBy('TenSanPham', 'asc');
                    break;
                    
                case 'name_desc':
                    $query->orderBy('TenSanPham', 'desc');
                    break;
                    
                case 'newest':
                default:
                    $query->latest('san_pham.created_at');
                    break;
            }
        } else {
            $query->latest('san_pham.created_at');
        }

        $sanPham = $query->paginate(12);

        return view('products.index', compact('sanPham', 'danhMuc'));
    }

    // Chi tiết sản phẩm
    public function show($id)
    {
        $sanPham = SanPham::with(['danhMuc', 'bienThe', 'binhLuan.nguoiDung'])
            ->findOrFail($id);

        // Sản phẩm liên quan (cùng danh mục)
        $sanPhamLienQuan = SanPham::with('bienThe')
            ->where('MaDanhMuc', $sanPham->MaDanhMuc)
            ->where('MaSanPham', '!=', $id)
            ->hienThi()
            ->take(4)
            ->get();

        // PHÂN TRANG ĐÁNH GIÁ - Chỉ lấy 5 đánh giá đầu tiên
        $danhGias = $sanPham->danhGia()
            ->daDuyet()
            ->with(['nguoiDung', 'nguoiPhanHoi', 'danhGiaHuuIch'])
            ->orderBy('NgayDanhGia', 'desc')
            ->paginate(5, ['*'], 'danh_gia_page'); // Thêm tên page riêng
        
        // PHÂN TRANG BÌNH LUẬN - Chỉ lấy 5 bình luận đầu tiên
        $binhLuans = $sanPham->binhLuan()
            ->goc()
            ->daDuyet()
            ->with(['replies.nguoiDung', 'nguoiDung'])
            ->orderBy('NgayBinhLuan', 'desc')
            ->paginate(5, ['*'], 'binh_luan_page');
            
        return view('products.show', compact('sanPham', 'sanPhamLienQuan', 'danhGias', 'binhLuans'));
    }

    // Tìm kiếm
    public function search(Request $request)
    {
        $keyword = $request->input('q');
        
        $sanPham = SanPham::with(['danhMuc', 'bienThe'])
            ->where('TenSanPham', 'like', '%' . $keyword . '%')
            ->orWhere('MoTa', 'like', '%' . $keyword . '%')
            ->hienThi()
            ->paginate(12);

        return view('products.search', compact('sanPham', 'keyword'));
    }
}