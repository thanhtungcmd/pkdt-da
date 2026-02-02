<?php
// database/seeders/DanhMucSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DanhMuc;

class DanhMucSeeder extends Seeder
{
    public function run(): void
    {
        $danhMuc = [
            [
                'TenDanhMuc' => 'Tai nghe',
                'MoTa' => 'Tai nghe gaming, tai nghe bluetooth, tai nghe có dây',
                'TrangThai' => 1,
            ],
            [
                'TenDanhMuc' => 'Chuột máy tính',
                'MoTa' => 'Chuột gaming, chuột văn phòng, chuột không dây',
                'TrangThai' => 1,
            ],
            [
                'TenDanhMuc' => 'Bàn phím',
                'MoTa' => 'Bàn phím cơ, bàn phím gaming, bàn phím văn phòng',
                'TrangThai' => 1,
            ],
            [
                'TenDanhMuc' => 'Loa bluetooth',
                'MoTa' => 'Loa bluetooth mini, loa karaoke, loa gaming',
                'TrangThai' => 1,
            ],
            [
                'TenDanhMuc' => 'Webcam',
                'MoTa' => 'Webcam học online, webcam streaming, webcam họp',
                'TrangThai' => 1,
            ],
            [
                'TenDanhMuc' => 'Sạc dự phòng',
                'MoTa' => 'Pin sạc dự phòng, sạc nhanh, sạc không dây',
                'TrangThai' => 1,
            ],
        ];

        foreach ($danhMuc as $dm) {
            DanhMuc::create($dm);
        }
    }
}