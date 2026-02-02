<?php
// app/Models/ThongBao.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'MaThongBao';

    protected $fillable = [
        'MaNguoiDung',
        'LoaiThongBao',
        'TieuDe',
        'NoiDung',
        'Link',
        'DaDoc',
        'ThoiGian',
    ];

    protected $casts = [
        'ThoiGian' => 'datetime',
        'DaDoc' => 'boolean',
    ];

    // Relationship với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Scope: Chỉ lấy thông báo chưa đọc
    public function scopeChuaDoc($query)
    {
        return $query->where('DaDoc', false);
    }

    // Scope: Chỉ lấy thông báo đã đọc
    public function scopeDaDoc($query)
    {
        return $query->where('DaDoc', true);
    }

    // Lấy icon theo loại thông báo
    public function icon()
    {
        $icons = [
            'comment_reply' => 'bi-chat-dots-fill text-primary',
            'review_reply' => 'bi-star-fill text-warning',
            'feedback_reply' => 'bi-reply-fill text-info',
            'order_confirmed' => 'bi-check-circle-fill text-success',
        ];
        
        return $icons[$this->LoaiThongBao] ?? 'bi-bell-fill text-secondary';
    }

    // Format thời gian hiển thị (vừa xong, 5 phút trước, ...)
    public function thoiGianHienThi()
    {
        return $this->ThoiGian->diffForHumans();
    }

    // Tạo thông báo mới (helper method)
    public static function taoThongBao($maNguoiDung, $loai, $tieuDe, $noiDung, $link = null)
    {
        return self::create([
            'MaNguoiDung' => $maNguoiDung,
            'LoaiThongBao' => $loai,
            'TieuDe' => $tieuDe,
            'NoiDung' => $noiDung,
            'Link' => $link,
            'ThoiGian' => Carbon::now(),
        ]);
    }
}