<?php
// app/Models/CTGioHang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTGioHang extends Model
{
    use HasFactory;

    protected $table = 'ct_gio_hang';
    protected $primaryKey = 'MaCTGioHang';

    protected $fillable = [
        'MaNguoiDung',
        'MaCTSanPham',
        'SoLuong',
        'DonGia',
        'NgayThem',
    ];

    protected $casts = [
        'NgayThem' => 'datetime',
    ];

    // Relationship với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Relationship với CTSanPham
    public function sanPham()
    {
        return $this->belongsTo(CTSanPham::class, 'MaCTSanPham', 'MaCTSanPham');
    }

    // Tính thành tiền
    public function thanhTien()
    {
        return $this->SoLuong * $this->DonGia;
    }
}