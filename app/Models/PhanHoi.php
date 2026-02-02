<?php
// app/Models/PhanHoi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanHoi extends Model
{
    use HasFactory;

    protected $table = 'phan_hoi';
    protected $primaryKey = 'MaPhanHoi';

    protected $fillable = [
        'MaNguoiDung',
        'TieuDe',
        'NoiDung',
        'NgayGui',
        'TrangThai',
    ];

    protected $casts = [
        'NgayGui' => 'datetime',
    ];

    // Relationship với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
    // relationships với TraLoiPhanHoi
    public function traLoi()
    {
        return $this->hasMany(TraLoiPhanHoi::class, 'MaPhanHoi', 'MaPhanHoi');
    }

    // Lấy phản hồi chưa xem
    public function scopeChuaXem($query)
    {
        return $query->where('TrangThai', 0);
    }

    // Lấy phản hồi đã xem
    public function scopeDaXem($query)
    {
        return $query->where('TrangThai', 1);
    }
}