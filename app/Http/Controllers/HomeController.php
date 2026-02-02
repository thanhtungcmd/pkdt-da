<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\DanhMuc;
use App\Models\SanPham;
use App\Models\TinTuc;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sanPhamBanChay = SanPham::with('bienThe')
            ->hienThi()
            ->withCount(['bienThe as soLuongBan' => function($query){
                $query->join('ct_don_hang','ct_san_pham.MaCTSanPham','=','ct_don_hang.MaCTSanPham')
                    ->selectRaw('SUM(ct_don_hang.SoLuong)');
            }])
            ->orderByDesc('soLuongBan')
            ->take(5)
            ->get();
        $danhMuc = DanhMuc::hienThi()->get();
        $sanPhamMoi = SanPham::with(['danhMuc', 'bienThe'])
            ->hienThi()
            ->latest()
            ->take(8)
            ->get();
        
        $tinTucMoi = TinTuc::with('nguoiDung')
            ->latest('NgayDang')
            ->take(3)
            ->get();

        return view('home', compact('danhMuc', 'sanPhamMoi', 'sanPhamBanChay', 'tinTucMoi'));
    }
}