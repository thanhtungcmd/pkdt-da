<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuMaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'lich_su_ma_giam_gia';
    protected $primaryKey = 'MaLichSu';

    protected $fillable = [
        'MaMaGiamGia',
        'MaNguoiDung',
        'MaDonHang',
        'SoTienGiam',
        'ThoiGianSuDung'
    ];

    protected $casts = [
        'ThoiGianSuDung' => 'datetime',
    ];

    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'MaMaGiamGia', 'MaMaGiamGia');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'MaDonHang', 'MaDonHang');
    }
}