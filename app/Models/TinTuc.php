<?php
// app/Models/TinTuc.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinTuc extends Model
{
    use HasFactory;

    protected $table = 'tin_tuc';
    protected $primaryKey = 'MaTinTuc';

    protected $fillable = [
        'MaNguoiDung',
        'TieuDe',
        'NoiDung',
        'AnhMinhHoa',
        'NgayDang',
    ];

    protected $casts = [
        'NgayDang' => 'datetime',
    ];

    // Relationship với NguoiDung (Người viết bài)
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Lấy đoạn tóm tắt
    public function tomTat($soKyTu = 150)
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->NoiDung), $soKyTu);
    }
}