<?php
// app/Models/DonHang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang';
    protected $primaryKey = 'MaDonHang';

    protected $fillable = [
        'MaNguoiDung',
        'NgayDat',
        'TongTien',
        'DiaChiGiaoHang',
        'PTThanhToan',
        'TrangThai',
        'MaMaGiamGia',
        'SoTienGiam',
    ];

    protected $casts = [
        'NgayDat' => 'datetime',
    ];

    // Relationship với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Relationship với CTDonHang
    public function chiTiet()
    {
        return $this->hasMany(CTDonHang::class, 'MaDonHang', 'MaDonHang');
    }

    // Relationship với DanhGia
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'MaDonHang', 'MaDonHang');
    }
    
    // Relationship với MaGiamGia
    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'MaMaGiamGia', 'MaMaGiamGia');
    }

    // Các trạng thái đơn hàng (đầy đủ để hiển thị trong hệ thống)
    const TRANG_THAI = [
        'Chờ xác nhận',
        'Đã xác nhận',
        'Đang giao hàng',
        'Đã giao hàng',
        'Đã hủy',
    ];

    // Màu sắc cho trạng thái
    public function mauTrangThai()
    {
        $mau = [
            'Chờ xác nhận' => 'warning',
            'Đã xác nhận' => 'info',
            'Đang giao hàng' => 'primary',
            'Đã giao hàng' => 'success',
            'Đã hủy' => 'danger',
        ];
        return $mau[$this->TrangThai] ?? 'secondary';
    }
}