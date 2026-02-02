<?php
// app/Models/CTDonHang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTDonHang extends Model
{
    use HasFactory;

    protected $table = 'ct_don_hang';
    protected $primaryKey = 'MaCTDonHang';

    protected $fillable = [
        'MaDonHang',
        'MaCTSanPham',
        'SoLuong',
        'DonGia',
        'ThanhTien',
    ];

    // Relationship với DonHang
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'MaDonHang', 'MaDonHang');
    }

    // Relationship với CTSanPham
    public function sanPham()
    {
        return $this->belongsTo(CTSanPham::class, 'MaCTSanPham', 'MaCTSanPham');
    }
}