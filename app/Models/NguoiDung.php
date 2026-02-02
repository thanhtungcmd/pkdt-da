<?php
// app/Models/NguoiDung.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'MaNguoiDung';

    protected $fillable = [
        'TenDangNhap',
        'Email',
        'MatKhau',
        'HoTen',
        'AnhDaiDien',
        'SoDienThoai',
        'DiaChi',
        'VaiTro',
        'TrangThai',
    ];

    protected $hidden = [
        'MatKhau',
        'remember_token',
    ];

    // Ghi đè phương thức để sử dụng 'MatKhau' thay vì 'password'
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    // Relationships
    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function tinTuc()
    {
        return $this->hasMany(TinTuc::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function phanHoi()
    {
        return $this->hasMany(PhanHoi::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function gioHang()
    {
        return $this->hasMany(CTGioHang::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Check admin
    public function isAdmin()
    {
        return $this->VaiTro == 1;
    }

    // Relationship với DanhGia
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'MaNguoiDung', 'MaNguoiDung');
    }
    
    // relationship với ThongBao
    public function thongBaos()
    {
        return $this->hasMany(ThongBao::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Đếm thông báo chưa đọc
    public function soThongBaoChuaDoc()
    {
        return $this->thongBaos()->chuaDoc()->count();
    }
}