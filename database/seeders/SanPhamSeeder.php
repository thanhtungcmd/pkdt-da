<?php
// database/seeders/SanPhamSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\CTSanPham;

class SanPhamSeeder extends Seeder
{
    public function run(): void
    {
        // TAI NGHE
        $sp1 = SanPham::create([
            'MaDanhMuc' => 1,
            'TenSanPham' => 'Tai nghe Gaming RGB Razer BlackShark V2',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=Razer+BlackShark',
            'MoTa' => 'Tai nghe gaming cao cấp với âm thanh 7.1, đèn RGB, mic tháo rời. Driver 50mm mang lại âm bass sâu.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp1->MaSanPham,
            'MauSac' => 'Đen',
            'DonGia' => 2490000,
            'SoLuongTon' => 50,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp1->MaSanPham,
            'MauSac' => 'Trắng',
            'DonGia' => 2590000,
            'SoLuongTon' => 30,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/FFFFFF/000000?text=White',
            'TrangThai' => 1,
        ]);

        $sp2 = SanPham::create([
            'MaDanhMuc' => 1,
            'TenSanPham' => 'Tai nghe Bluetooth Sony WH-1000XM5',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=Sony+WH1000XM5',
            'MoTa' => 'Tai nghe chống ồn hàng đầu, pin 30 giờ, âm thanh Hi-Res, kết nối đa điểm.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp2->MaSanPham,
            'MauSac' => 'Đen',
            'DonGia' => 7990000,
            'SoLuongTon' => 25,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp2->MaSanPham,
            'MauSac' => 'Bạc',
            'DonGia' => 7990000,
            'SoLuongTon' => 20,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/C0C0C0/000000?text=Silver',
            'TrangThai' => 1,
        ]);

        // CHUỘT
        $sp3 = SanPham::create([
            'MaDanhMuc' => 2,
            'TenSanPham' => 'Chuột Gaming Logitech G502 Hero',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=Logitech+G502',
            'MoTa' => 'Chuột gaming với sensor HERO 25K, 11 nút lập trình được, trọng lượng tùy chỉnh.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp3->MaSanPham,
            'MauSac' => 'Đen',
            'DonGia' => 1290000,
            'SoLuongTon' => 60,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black',
            'TrangThai' => 1,
        ]);

        $sp4 = SanPham::create([
            'MaDanhMuc' => 2,
            'TenSanPham' => 'Chuột không dây Logitech MX Master 3S',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=MX+Master+3S',
            'MoTa' => 'Chuột văn phòng cao cấp, pin 70 ngày, cuộn siêu nhanh, kết nối 3 thiết bị.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp4->MaSanPham,
            'MauSac' => 'Đen',
            'DonGia' => 2390000,
            'SoLuongTon' => 40,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp4->MaSanPham,
            'MauSac' => 'Xám',
            'DonGia' => 2390000,
            'SoLuongTon' => 35,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/808080/FFFFFF?text=Gray',
            'TrangThai' => 1,
        ]);

        // BÀN PHÍM
        $sp5 = SanPham::create([
            'MaDanhMuc' => 3,
            'TenSanPham' => 'Bàn phím cơ Keychron K8 Pro',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=Keychron+K8',
            'MoTa' => 'Bàn phím cơ không dây, hot-swap, RGB, tương thích Mac/Windows.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp5->MaSanPham,
            'MauSac' => 'Đen',
            'KichThuoc' => 'Red Switch',
            'DonGia' => 2290000,
            'SoLuongTon' => 45,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black+Red',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp5->MaSanPham,
            'MauSac' => 'Đen',
            'KichThuoc' => 'Blue Switch',
            'DonGia' => 2290000,
            'SoLuongTon' => 40,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/0066FF?text=Black+Blue',
            'TrangThai' => 1,
        ]);

        // LOA
        $sp6 = SanPham::create([
            'MaDanhMuc' => 4,
            'TenSanPham' => 'Loa Bluetooth JBL Flip 6',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=JBL+Flip+6',
            'MoTa' => 'Loa bluetooth chống nước IP67, pin 12 giờ, âm bass mạnh mẽ.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp6->MaSanPham,
            'MauSac' => 'Đen',
            'DonGia' => 2990000,
            'SoLuongTon' => 55,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp6->MaSanPham,
            'MauSac' => 'Xanh dương',
            'DonGia' => 2990000,
            'SoLuongTon' => 48,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=Blue',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp6->MaSanPham,
            'MauSac' => 'Đỏ',
            'DonGia' => 2990000,
            'SoLuongTon' => 42,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/FF0000/FFFFFF?text=Red',
            'TrangThai' => 1,
        ]);

        // WEBCAM
        $sp7 = SanPham::create([
            'MaDanhMuc' => 5,
            'TenSanPham' => 'Webcam Logitech C920 Pro HD',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=C920+Pro',
            'MoTa' => 'Webcam Full HD 1080p, autofocus, mic stereo, ideal for streaming.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp7->MaSanPham,
            'MauSac' => 'Đen',
            'DonGia' => 1790000,
            'SoLuongTon' => 65,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=Black',
            'TrangThai' => 1,
        ]);

        // SẠC DỰ PHÒNG
        $sp8 = SanPham::create([
            'MaDanhMuc' => 6,
            'TenSanPham' => 'Pin sạc dự phòng Anker PowerCore 20000mAh',
            'AnhChinh' => 'https://via.placeholder.com/600x600/0066FF/FFFFFF?text=Anker+20000',
            'MoTa' => 'Sạc nhanh 20W, 2 cổng USB, công nghệ PowerIQ, sạc iPhone đến 4 lần.',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp8->MaSanPham,
            'MauSac' => 'Đen',
            'DungLuong' => '20000mAh',
            'DonGia' => 890000,
            'SoLuongTon' => 80,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/000000/FFFFFF?text=20K+Black',
            'TrangThai' => 1,
        ]);

        CTSanPham::create([
            'MaSanPham' => $sp8->MaSanPham,
            'MauSac' => 'Trắng',
            'DungLuong' => '20000mAh',
            'DonGia' => 890000,
            'SoLuongTon' => 75,
            'AnhMinhHoa' => 'https://via.placeholder.com/600x600/FFFFFF/000000?text=20K+White',
            'TrangThai' => 1,
        ]);
    }
}