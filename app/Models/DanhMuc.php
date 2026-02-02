<?php
// app/Models/DanhMuc.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    protected $table = 'danh_muc';
    protected $primaryKey = 'MaDanhMuc';

    protected $fillable = [
        'TenDanhMuc',
        'MoTa',
        'TrangThai',
        'AnhMinhHoa'
    ];

    // Relationship với SanPham
    public function sanPham()
    {
        return $this->hasMany(SanPham::class, 'MaDanhMuc', 'MaDanhMuc');
    }

    // Lấy danh mục đang hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('TrangThai', 1);
    }
}