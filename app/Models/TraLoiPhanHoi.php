<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraLoiPhanHoi extends Model
{
    use HasFactory;

    protected $table = 'tra_loi_phan_hoi';
    protected $primaryKey = 'MaTraLoi';

    protected $fillable = [
        'MaPhanHoi',
        'MaAdmin',
        'NoiDung',
        'NgayTraLoi',
    ];

    protected $casts = [
        'NgayTraLoi' => 'datetime',
    ];

    public function phanHoi()
    {
        return $this->belongsTo(PhanHoi::class, 'MaPhanHoi', 'MaPhanHoi');
    }

    public function admin()
    {
        return $this->belongsTo(NguoiDung::class, 'MaAdmin', 'MaNguoiDung');
    }
}