<?php
// app/Models/SanPham.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DanhGia;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    protected $primaryKey = 'MaSanPham';

    protected $fillable = [
        'MaDanhMuc',
        'TenSanPham',
        'ThuongHieu',
        'AnhChinh',
        'MoTa',
        'TrangThai',
    ];

    // Relationship với DanhMuc
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'MaDanhMuc', 'MaDanhMuc');
    }

    // Relationship với CTSanPham (Biến thể)
    public function bienThe()
    {
        return $this->hasMany(CTSanPham::class, 'MaSanPham', 'MaSanPham');
    }

    // Relationship với BinhLuan
    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'MaSanPham', 'MaSanPham');
    }

    // Lấy sản phẩm đang hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('san_pham.TrangThai', 1);
    }

    // Lấy giá thấp nhất của sản phẩm
    public function giaThapNhat()
    {
        return $this->bienThe()->min('DonGia');
    }

    // Lấy giá cao nhất của sản phẩm
    public function giaCaoNhat()
    {
        return $this->bienThe()->max('DonGia');
    }

    // Relationship với DanhGia
    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'MaSanPham', 'MaSanPham');
    }

    // Tính trung bình số sao
    public function trungBinhSao()
    {
        return $this->danhGia()->daDuyet()->avg('SoSao') ?? 0;
    }

    // Đếm số lượng đánh giá
    public function tongDanhGia()
    {
        return $this->danhGia()->daDuyet()->count();
    }

    // Đếm số lượng theo từng mức sao
    public function demTheoSao($soSao)
    {
        return $this->danhGia()->daDuyet()->where('SoSao', $soSao)->count();
    }
}