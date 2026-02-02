<?php
// app/Models/DanhGiaHuuIch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGiaHuuIch extends Model
{
    use HasFactory;

    protected $table = 'danh_gia_huu_ich';
    protected $primaryKey = 'MaHuuIch';

    protected $fillable = [
        'MaDanhGia',
        'MaNguoiDung',
        'HuuIch',
    ];

    protected $casts = [
        'HuuIch' => 'boolean',
    ];

    // Relationship với DanhGia
    public function danhGia()
    {
        return $this->belongsTo(DanhGia::class, 'MaDanhGia', 'MaDanhGia');
    }

    // Relationship với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}