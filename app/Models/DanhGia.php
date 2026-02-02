<?php
// app/Models/DanhGia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danh_gia';
    protected $primaryKey = 'MaDanhGia';

    protected $fillable = [
        'MaNguoiDung',
        'MaSanPham',
        'MaDonHang',
        'SoSao',
        'NoiDung',
        'HinhAnh',
        'TrangThai',
        'DaXem',
        'PhanHoiShop',
        'NguoiPhanHoi',
        'NgayPhanHoi',
        'NgayDanhGia',
    ];

    protected $casts = [
        'NgayDanhGia' => 'datetime',
        'NgayPhanHoi' => 'datetime',
        'SoSao' => 'integer',
        'HinhAnh' => 'array', // Cast JSON thành array
        'DaXem' => 'boolean',
    ];

    // Relationship với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Relationship với SanPham
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSanPham', 'MaSanPham');
    }

    // Relationship với DonHang
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'MaDonHang', 'MaDonHang');
    }

    // Scope: Chỉ lấy đánh giá đã duyệt
    public function scopeDaDuyet($query)
    {
        return $query->where('TrangThai', 'da_duyet');
    }

    // Scope: Lọc theo số sao
    public function scopeTheoSao($query, $soSao)
    {
        return $query->where('SoSao', $soSao);
    }

    // Relationship với NguoiPhanHoi (Admin)
    public function nguoiPhanHoi()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiPhanHoi', 'MaNguoiDung');
    }

    // Relationship với DanhGiaHuuIch
    public function danhGiaHuuIch()
    {
        return $this->hasMany(DanhGiaHuuIch::class, 'MaDanhGia', 'MaDanhGia');
    }

    // Đếm số lượng vote hữu ích (like)
    public function tongHuuIch()
    {
        return $this->danhGiaHuuIch()->where('HuuIch', true)->count();
    }

    // Đếm số lượng vote không hữu ích (dislike)
    public function tongKhongHuuIch()
    {
        return $this->danhGiaHuuIch()->where('HuuIch', false)->count();
    }

    // Kiểm tra user đã vote chưa
    public function daVoteBoi($maNguoiDung)
    {
        return $this->danhGiaHuuIch()->where('MaNguoiDung', $maNguoiDung)->first();
    }

    // Scope: Chỉ lấy đánh giá chưa xem
    public function scopeChuaXem($query)
    {
        return $query->where('DaXem', false);
    }
}