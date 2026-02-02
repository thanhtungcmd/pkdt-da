<?php
// app/Models/CTSanPham.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTSanPham extends Model
{
    use HasFactory;

    protected $table = 'ct_san_pham';
    protected $primaryKey = 'MaCTSanPham';

    protected $fillable = [
        'MaSanPham',
        'MauSac',
        'DungLuong',
        'KichThuoc',
        'DonGia',
        'SoLuongTon',
        'AnhMinhHoa',
        'TrangThai',
    ];

    // Relationship với SanPham
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSanPham', 'MaSanPham');
    }

    // Relationship với CTDonHang
    public function chiTietDonHang()
    {
        return $this->hasMany(CTDonHang::class, 'MaCTSanPham', 'MaCTSanPham');
    }

    // Relationship với CTGioHang
    public function gioHang()
    {
        return $this->hasMany(CTGioHang::class, 'MaCTSanPham', 'MaCTSanPham');
    }

    // Lấy biến thể đang hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('TrangThai', 1);
    }

    // Kiểm tra còn hàng
    public function conHang()
    {
        return $this->SoLuongTon > 0;
    }

    // Lấy tên đầy đủ (Sản phẩm - Màu - Dung lượng)
    public function tenDayDu()
    {
        $ten = $this->sanPham->TenSanPham . ' - ' . $this->MauSac;
        if ($this->DungLuong) {
            $ten .= ' - ' . $this->DungLuong;
        }
        if ($this->KichThuoc) {
            $ten .= ' - ' . $this->KichThuoc;
        }
        return $ten;
    }
}