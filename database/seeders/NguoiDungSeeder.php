<?php
// database/seeders/NguoiDungSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;

class NguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        NguoiDung::create([
            'TenDangNhap' => 'admin',
            'Email' => 'admin@phukien.com',
            'MatKhau' => Hash::make('admin123'),
            'HoTen' => 'Quản Trị Viên',
            'SoDienThoai' => '0901234567',
            'DiaChi' => 'Hà Nội',
            'VaiTro' => 1,
            'TrangThai' => 1,
        ]);

        // Khách hàng 1
        NguoiDung::create([
            'TenDangNhap' => 'khach1',
            'Email' => 'khach1@gmail.com',
            'MatKhau' => Hash::make('123456'),
            'HoTen' => 'Nguyễn Văn A',
            'SoDienThoai' => '0912345678',
            'DiaChi' => '123 Đường Láng, Đống Đa, Hà Nội',
            'VaiTro' => 0,
            'TrangThai' => 1,
        ]);

        // Khách hàng 2
        NguoiDung::create([
            'TenDangNhap' => 'khach2',
            'Email' => 'khach2@gmail.com',
            'MatKhau' => Hash::make('123456'),
            'HoTen' => 'Trần Thị B',
            'SoDienThoai' => '0923456789',
            'DiaChi' => '456 Giải Phóng, Hai Bà Trưng, Hà Nội',
            'VaiTro' => 0,
            'TrangThai' => 1,
        ]);

        // Khách hàng 3
        NguoiDung::create([
            'TenDangNhap' => 'khach3',
            'Email' => 'khach3@gmail.com',
            'MatKhau' => Hash::make('123456'),
            'HoTen' => 'Lê Văn C',
            'SoDienThoai' => '0934567890',
            'DiaChi' => '789 Cầu Giấy, Hà Nội',
            'VaiTro' => 0,
            'TrangThai' => 1,
        ]);
    }
}