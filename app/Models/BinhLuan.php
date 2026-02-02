<?php
// app/Models/BinhLuan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luan';
    protected $primaryKey = 'MaBinhLuan';

    protected $fillable = [
        'MaNguoiDung',
        'MaSanPham',
        'NoiDung',
        'NgayBinhLuan',
        'TrangThai',
        'MaBinhLuanCha',
        'DaXem',
    ];

    protected $casts = [
        'NgayBinhLuan' => 'datetime',
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

    // Bình luận cha (bình luận gốc)
    public function parent()
    {
        return $this->belongsTo(BinhLuan::class, 'MaBinhLuanCha', 'MaBinhLuan');
    }

    // Các bình luận con (trả lời)
    public function replies()
    {
        return $this->hasMany(BinhLuan::class, 'MaBinhLuanCha', 'MaBinhLuan')
                    ->where('TrangThai', 'da_duyet')
                    ->orderBy('NgayBinhLuan', 'asc');
    }

    // Scope: Chỉ lấy bình luận đã duyệt
    public function scopeDaDuyet($query)
    {
        return $query->where('TrangThai', 'da_duyet');
    }

    // Scope: Chỉ lấy bình luản gốc (không phải trả lời)
    public function scopeGoc($query)
    {
        return $query->whereNull('MaBinhLuanCha');
    }

    // Scope: Bình luận chưa xem
    public function scopeChuaXem($query)
    {
        return $query->where('DaXem', false);
    }

    // Kiểm tra user đã mua sản phẩm này chưa
    public function daMuaHang()
    {
        return $this->nguoiDung->donHang()
            ->where('TrangThai', 'hoan_thanh')
            ->whereHas('chiTiet.sanPham.sanPham', function($q) {
                $q->where('MaSanPham', $this->MaSanPham);
            })
            ->exists();
    }
}