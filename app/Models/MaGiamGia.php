<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gia';
    protected $primaryKey = 'MaMaGiamGia';

    protected $fillable = [
        'MaCode',
        'LoaiGiam',
        'GiaTri',
        'DonToiThieu',
        'GiamToiDa',
        'GioiHanSuDung',
        'GioiHanMoiNguoi',
        'DaSuDung',
        'NgayBatDau',
        'NgayKetThuc',
        'TrangThai',
        'MoTa'
    ];

    protected $casts = [
        'NgayBatDau' => 'datetime',
        'NgayKetThuc' => 'datetime',
        'TrangThai' => 'boolean',
    ];

    // Quan hệ với bảng lich_su_ma_giam_gia
    public function lichSuSuDung()
    {
        return $this->hasMany(LichSuMaGiamGia::class, 'MaMaGiamGia', 'MaMaGiamGia');
    }

    // Quan hệ với đơn hàng
    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'MaMaGiamGia', 'MaMaGiamGia');
    }

    // Kiểm tra mã có hợp lệ không
    public function kiemTraHopLe($maNguoiDung = null)
    {
        // Nếu không truyền tham số, lấy user hiện tại
        if ($maNguoiDung === null && auth()->check()) {
            $maNguoiDung = auth()->id();
        }

        // Kiểm tra trạng thái active
        if (!$this->TrangThai) {
            return ['valid' => false, 'message' => 'Mã giảm giá không còn hiệu lực'];
        }

        // Kiểm tra ngày bắt đầu
        if ($this->NgayBatDau && Carbon::now()->lt($this->NgayBatDau)) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa đến thời gian sử dụng'];
        }

        // Kiểm tra ngày hết hạn
        if ($this->NgayKetThuc && Carbon::now()->gt($this->NgayKetThuc)) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn'];
        }

        // Kiểm tra giới hạn sử dụng
        if ($this->GioiHanSuDung && $this->DaSuDung >= $this->GioiHanSuDung) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
        }

        //Kiểm tra giới hạn mỗi người dùng
        if ($maNguoiDung && $this->GioiHanMoiNguoi) {
            $soLanDaDung = $this->soLanDaDungBoiNguoiDung($maNguoiDung);
            if ($soLanDaDung >= $this->GioiHanMoiNguoi) {
                return ['valid' => false, 'message' => 'Bạn đã sử dụng hết lượt cho mã này'];
            }
        }

        return ['valid' => true, 'message' => 'Mã giảm giá hợp lệ'];
    }

    // Tính toán số tiền giảm giá
    public function tinhTienGiam($tongTien)
    {
        // Kiểm tra đơn hàng tối thiểu
        if ($this->DonToiThieu && $tongTien < $this->DonToiThieu) {
            return [
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($this->DonToiThieu, 0, ',', '.') . 'đ',
                'discount' => 0
            ];
        }

        $soTienGiam = 0;

        if ($this->LoaiGiam === 'fixed') {
            // Giảm giá cố định
            $soTienGiam = $this->GiaTri;
        } elseif ($this->LoaiGiam === 'percent') {
            // Giảm giá theo phần trăm
            $soTienGiam = ($tongTien * $this->GiaTri) / 100;
            
            // Áp dụng giảm giá tối đa nếu có
            if ($this->GiamToiDa && $soTienGiam > $this->GiamToiDa) {
                $soTienGiam = $this->GiamToiDa;
            }
        }

        // Không cho giảm quá tổng đơn hàng
        if ($soTienGiam > $tongTien) {
            $soTienGiam = $tongTien;
        }

        return [
            'success' => true,
            'message' => 'Áp dụng mã thành công',
            'discount' => $soTienGiam
        ];
    }

    // Tăng số lần sử dụng
    public function tangSoLanSuDung()
    {
        $this->increment('DaSuDung');
    }

    // Scope để lấy mã đang active
    public function scopeActive($query)
    {
        return $query->where('TrangThai', true)
                    ->where(function($q) {
                        $q->whereNull('NgayBatDau')
                          ->orWhere('NgayBatDau', '<=', Carbon::now());
                    })
                    ->where(function($q) {
                        $q->whereNull('NgayKetThuc')
                          ->orWhere('NgayKetThuc', '>=', Carbon::now());
                    });
    }

    // Màu sắc cho badge trạng thái
    public function mauTrangThai()
    {
        $validation = $this->kiemTraHopLe();
        return $validation['valid'] ? 'success' : 'danger';
    }
    // Đếm số lần user đã dùng mã này
    public function soLanDaDungBoiNguoiDung($maNguoiDung)
    {
        return $this->lichSuSuDung()
                    ->where('MaNguoiDung', $maNguoiDung)
                    ->count();
    }
}